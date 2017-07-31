<?php
/**
 * Created by PhpStorm.
 * User: long-yun-rui
 * Date: 2017/3/13
 * Time: 14:24
 * Description:七牛云上传
 */
namespace App\Libraries\Tab;
// 七牛上传类
use App\Libraries\Qiniu\FileManagement;

class QiNiuUpload implements Upload
{
    public function __construct()
    {
    }

    public function upload($filename, $realPath)
    {
        $uploadModel = new FileManagement();
        $uploadToQiNiuResult = $uploadModel->testFileUpload($filename, $realPath);
        if (empty($uploadToQiNiuResult)) {
            $return['error'] = "服务器忙,请稍后再试";
            $return['code'] = -1;
            return $return;
        }
        // 还有些细节没处理，将就用
        $uploadToQiNiuResult['code'] = 200;
        return $uploadToQiNiuResult;
    }

    public function download($file_key, $file_name)
    {
        $uploadModel = new FileManagement();
        $uploadToQiNiuResult = $uploadModel->testFileDownload($file_key, $file_name);
        return  $uploadToQiNiuResult;
    }
}