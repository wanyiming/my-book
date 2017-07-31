<?php

namespace App\Http\Controllers\Home;

use App\Models\DisFile;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid;
use Storage;
use stdClass;
use Response;
use DB;
use Log;
use Exception;
use Throwable;

/**
 * 附件上传类
 *
 * Class AttachmentController
 * @package App\Http\Controllers\Home
 */
class AttachmentController extends Controller
{
    protected $allowExtensions = [];//允许的类型

    public function demo()
    {
        return view('home.attachment.demo');
    }

    //附件上传
    public function upload(Request $request)
    {
        try {
            $ret = new stdClass();
            $allFiles = $request->allFiles();
            foreach ($allFiles as $fileName => $files) {
                if (!is_array($files)) {
                    $files = [$files];
                }
                $ret->{$fileName} = $this->doFiles($files);
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
    protected function doFiles(array $files)
    {
        $retFiles = [];
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

            if (!in_array(strtolower($ext), ["txt","zip", "rar", "tar", "pdf", "jpg", "jpeg", "png", "bmp", "doc", "excel", "xlsx", "docx", "xls"])) {
                $retFile->message = "文件格式不允许";
                continue;
            }

            $filename = date('Ymd') . DIRECTORY_SEPARATOR . date('His') . uniqid() . '.' . $ext;
            // 使用我们新建的uploads本地存储空间（目录）
            $bool = Storage::disk('uploads')->put($filename, file_get_contents($realPath));
            if (empty($bool)) {
                $retFile->message = "服务器忙,请稍后再试";
                continue;
            }

            //
            $retFile->url = (str_replace('\\', '/', "/uploads/{$filename}"));
            $retFile->name = $originalName;
            $retFile->size = $file->getSize();
            $retFile->size_cn = round($file->getSize() / (1024 * 1024), 2);
            $retFile->now_time = date('Y-m-d H:i:s');
            $retFile->error = 0;
            $retFile->ext = $ext;

            DB::beginTransaction();
            $file_id = Uuid::uuid4()->toString();
            //写入数据库
            $insertBool = DisFile::insert([
                'file_sha1'  => sha1_file($realPath),
                'file_md5'   => md5_file($realPath),
                'file_name'  => $originalName,
                'file_size'  => $retFile->size,
                'created_at' => date('Y-m-d H:i:s'),
                'visit_path' => str_replace('\\', '/', "/uploads/{$filename}"),
                'file_id'    => $file_id,
                'cited_num'  => 0,
                'ext'        => $retFile->ext,
                'attribute'  => json_encode((object)[]),
            ]);

            if (!$insertBool) {
                $retFile->message = "服务器忙,请稍后再试";
                DB::rollBack();
                continue;
            }

            $retFile->file_id = $file_id;
            DB::commit();


        }
        return $retFiles;
    }


    /**
     * 文件下载
     * @param string $file_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\BinaryFileResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function download(string $file_id){
        $fileInfo = DisFile::where('file_id','=',$file_id)->first();
        if(empty($fileInfo)){
            return response('文件不存在');
        }
        try {
            return response()->download(public_path($fileInfo['visit_path']),$fileInfo['file_name']);
        } catch (Exception $e) {
            Log::error('文件下载错误:'.$e->getMessage());
            return error_show_msg('请求错误',url('/'));
        }
    }

}
