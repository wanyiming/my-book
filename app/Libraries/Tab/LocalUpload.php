<?php
namespace App\Libraries\Tab;

/**
 * Created by PhpStorm.
 * User: long-yun-rui
 * Date: 2017/3/13
 * Time: 14:24
 * Description:本地上传
 */
use Storage;

class LocalUpload implements Upload
{
    public function __construct()
    {
    }

    public function upload($filename, $realPath)
    {
        $filename_path = date('Ymd') . DIRECTORY_SEPARATOR . $filename;
        // 使用我们新建的uploads本地存储空间（目录）
        $bool = Storage::disk('uploads')->put($filename_path, file_get_contents($realPath));
        if (empty($bool)) {
            $return['error'] = "服务器忙,请稍后再试";
            $return['code'] = -1;
            return $return;
        }
        $return['hash'] = "";
        $return['key'] = (str_replace('\\', '/', "/uploads/{$filename_path}"));;
        $return['code'] = 200;
        return $return;
    }

    public function download($file_key, $file_name)
    {

    }
}