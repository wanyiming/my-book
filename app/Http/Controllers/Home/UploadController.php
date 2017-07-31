<?php

namespace App\Http\Controllers\Home;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// 文件上传需要引入的类
use App\Models\SysSetting;
use App\Libraries\Tab\TabUpload;
use App\Libraries\Tab\QiNiuUpload;
use App\Libraries\Tab\LocalUpload;
use Exception;
use Throwable;
use Log;
use Response;

class UploadController extends Controller
{
    //附件上传
    public function upload(Request $request)
    {
        try {
            // 获取上传的文件信息
            $file = $request->allFiles();
            // 在本地先验证
            $default_type = SysSetting::UPLOAD_TYPE_TAB_ATTACHMENT;
            $upload_type = $request->input('up_type',$default_type);
            $type_name = (new SysSetting())->returnUploadTypeValue($upload_type);
            $type_info = (new SysSetting())->getValue($type_name);

            // 生成依赖（使用七牛云上传,切换到本地上传时，请将.env文件中的QINIU_STATUE改为false，这里其实可以改成自动的，但是时间不够，将就用吧）
            $uploadMethod =  new QiNiuUpload();
            //$uploadMethod =  new LocalUpload();
            // 注入依赖（手动注入已经够用了吧？）
            $uploadModel = new TabUpload( $uploadMethod );
            $user_info['user_type'] = TabUpload::UPLOAD_USER_TYPE_USER;     // 记录上传用户的类型，1 前台用户（有限制）；2 后台用户（后台用户暂时没有限制）
            $user_info['id'] = get_user_session_info('user_uuid');          // 前台用户的user_uuid
            $result = $uploadModel->uploadAttachment($file,$type_info,$user_info);

            return $result;
        } catch (Exception $e) {
            Log::warning($e);
        } catch (Throwable $e) {
            Log::warning($e);
        }

        return Response::json(['error' => -1, 'message' => '系统异常,请稍后再试']);
    }
}
