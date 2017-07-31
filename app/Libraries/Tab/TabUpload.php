<?php
namespace App\Libraries\Tab;
/**
 * Created by PhpStorm.
 * User: long-yun-rui
 * Date: 2017/3/13
 * Time: 14:25
 * Description: 核心方法还是使用的问问我2.0的文件处理方法，只是在此基础上进行了更符合需求的改进
 */
use Response;
use Exception;
use Log;
use Throwable;
use stdClass;
use Storage;
use DB;
use Ramsey\Uuid\Uuid;
use App\Models\DisFile;


class TabUpload {

    private $uploadMethod;
    private $user_type;
    private $user_id;

    const UPLOAD_USER_TYPE_USER = 1; // 前台用户上传
    const UPLOAD_USER_TYPE_ADMIN = 2; // 后台管理员上传

    public function __construct( Upload $uploadMethod)
    {
        $this->uploadMethod= $uploadMethod;
    }

    public function  uploadAttachment($allFiles,$type_info,$user_info)
    {
        if(empty($user_info)){
            return response_error('用户不存在，请登录后再上传');
        }
        $this->user_type= $user_info['user_type'];
        $this->user_id= $user_info['id'];
        // 今天已上传文件的相关检查
        // 检查当前用户今天上传的附件个数，附件总大小
        $todayFileCheckResult = $this->todayFileCheck($type_info);
        if($todayFileCheckResult){
            return $todayFileCheckResult;
        }
        // 验证上传的文件是否符合要求
        $upCheckResult = $this->upFileCheck($allFiles,$type_info);
        if($upCheckResult){
            return $upCheckResult;
        }
    }

    // 上传的文件相关验证检查
    public function upFileCheck($file,$type_info){
        try {
            $ret = new stdClass();
            foreach ($file as $fileName => $files) {
                if (!is_array($files)) {
                    $files = [$files];
                }
                $ret->{$fileName} = $this->doFiles($files,$type_info);
            }
            //HTTP_USER_AGENT 兼容IE系列
            if (stripos($_SERVER['HTTP_USER_AGENT'] ?? "", 'MSIE')) {
                return Response::json($ret, 200, ['Content-Type' => 'text/plain']);
            }
            return Response::json($ret);
        } catch (Exception $e) {
            Log::warning($e);
        } catch (Throwable $e) {
            Log::warning($e);
        }

        return Response::json(['error' => -1, 'message' => '系统异常,请稍后再试']);
    }

    //处理文件
    protected function doFiles(array $files,$type_info)
    {
        $retFiles = [];
        $qiNiuStatus = env('QINIU_STATUE',false);
        $resource_domain = '';
        if($qiNiuStatus){
            $resource_domain = env('RESOURCE_DOMAIN','/');
        }
        $user_uuid = get_user_session_info('user_uuid');
        $admin_id = get_admin_session_info('id');
        foreach ($files as $file) {
            $retFile = new stdClass();
            $retFiles[] = $retFile;
            $retFile->error = -1;
            if (!$file->isValid()) {
                $retFile->message = "上传失败";
                continue;
            }

            $originalName = $file->getClientOriginalName(); // 文件原名
            $ext = $file->getClientOriginalExtension();     // 扩展名
            $realPath = $file->getRealPath();               // 临时文件的绝对路径
            //$type = $file->getClientMimeType();           // image/jpeg


            $ext_arr = $type_info['mime_types'];
            if (!in_array(strtolower($ext), explode(',',$ext_arr))) {
                $retFile->message = "文件格式不允许";
                continue;
            }
            $filename =  date('His') . uniqid() . '.' . $ext;   // 文件名

            // 开始上传
            $uploadToServerResult = $this->uploadMethod->upload($filename,$realPath);
            if($uploadToServerResult['code'] != 200){
                $retFile->message = $uploadToServerResult['error'];
                continue;
            }
            /**
             * 七牛返回的数据
             * [hash] => FpRdASdG8dsfGspzlfGaeiO9VOmk
             * [key] => 10261558c8a6478e0ef.docx
             * 资源文件路径
             * http://omu19gjvl.bkt.clouddn.com/10150458c8a3a8ed6e3.docx
             */

            // 本地的时候就是当前域名+上传相对路径
            // 方在第三方平台的时候就是资源域名+相对路径

            $retFile->name = $originalName;     // 文件原名
            $retFile->size = $file->getSize();  // 文件大小
            $retFile->size_cn = round($file->getSize() / (1024 * 1024), 2);
            $retFile->now_time = date('Y-m-d H:i:s');   // 上传时间
            $retFile->error = 0;    // 错误信息
            $retFile->ext = $ext;   // 文件后缀



            DB::beginTransaction();
            $file_id = Uuid::uuid4()->toString();
            //写入数据库 @TODO 此点需要优化
            $save_data['file_sha1'] = sha1_file($realPath);
            $save_data['file_md5'] = md5_file($realPath);
            $save_data['file_name'] = $originalName;
            $save_data['file_size'] = $retFile->size;
            $save_data['created_at'] = date('Y-m-d H:i:s');
            $save_data['visit_path'] = $resource_domain.$uploadToServerResult['key'];
            $save_data['file_id'] = $file_id;
            $save_data['cited_num'] = 0;
            $save_data['ext'] = $retFile->ext;
            $save_data['attribute'] = json_encode((object)[]);
            $save_data['file_hash'] = $uploadToServerResult['hash'];
            if($this->user_type == self::UPLOAD_USER_TYPE_USER){
                $save_data['user_uuid'] = $user_uuid;
            }else{
                $save_data['admin_id'] = $admin_id;
            }
            $insertBool = DisFile::insert($save_data);

            if (!$insertBool) {
                $retFile->message = "服务器忙,请稍后再试";
                DB::rollBack();
                continue;
            }

            $retFile->file_id = $file_id;
            $retFile->download_url = route('home.download',['file_id'=>$file_id]);
            DB::commit();


        }
        return $retFiles;
    }

    // 当天已上传的文件相关验证检查
    public function todayFileCheck($type_info){
        $max_file_size = $type_info['max_file_size'] ?? 0;
        $max_total_size = $type_info['max_total_size'] ?? 0;
        $max_total_num = $type_info['max_total_num'] ?? 0;
        $mime_types = $type_info['mime_types'] ?? 0;
        // 如果是后台人员上传附件，则暂时不做限制
        if($this->user_type == self::UPLOAD_USER_TYPE_ADMIN){
            return false;
        }
        // 检查当前用户今天上传的附件个数，附件总大小
        $user_uuid = get_user_session_info('user_uuid');
        $today_time = date('Y-m-d');
        $list = DisFile::where(['user_uuid'=>$user_uuid,['created_at','>',$today_time]])->get()->toArray();
        if(!empty($list)){
            // 今天已上传的文件个数
            $file_num = count($list);
            if($file_num >=  $max_total_num){
                return response_error('今天上传的附件个数已达到限额，如有需要，请联系客服');
            }
            // 今天已上传的文件总大小
            $file_size = 0;
            foreach ($list as $value){
                $file_size += $value['file_size'];
            }
            if($file_size > bcmul($max_file_size,1024)){
                return response_error('今天上传的附件大小已达到限额，如有需要，请联系客服');
            }
        }
        return false;
    }

    // 文件下载
    public function downloadAttachment(string $file_id){
        $file_info = $this->getFileInfo($file_id);
        if(!$file_info){
            error_show_msg('请求出错，请刷新后再试');
        }
//        $file_key = $file_info->file_hash;
        // 目前预览是直接打开连接地址，但是下载又经过一次处理，所以暂时用线上资源文件的路径做key，
        // 如果以后优化，请先将预览功能改为由服务器处理后的数据
        $file_key = $file_info->visit_path;
        $file_name = $file_info->file_name;
        // 开始下载
//        $url = $this->uploadMethod->download($file_key,$file_name);   // 此段为以后优化后使用，勿删！
        $url = $file_key;
        sys_download_file($url,$file_name,true);
    }

    // 获取文件信息，附件预览也可以这样优化（即：点击预览的时候请求服务端，服务端处理提交上来的文件id处理后返回预览的实际地址，时间关系，以后优化再做）
    public function getFileInfo($file_id){
        if($file_id === ''){
            return false;
        }
        // 根据file_id获取文件信息
        $file_info = DisFile::where(['file_id'=>$file_id])->first();
        if($file_info){
            return $file_info;
        }
        return false;
    }
}