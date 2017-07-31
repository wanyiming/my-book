<?php
namespace App\Libraries\Tab;
/**
 * Created by PhpStorm.
 * User: long-yun-rui
 * Date: 2017/3/13
 * Time: 14:27
 * Description:上传类接口
 */
interface Upload
{
    /**
     *
     * @param $filename     // 文件名
     * @param $realPath     // 上传文件的临时绝对路径
     * @return mixed
     * @User: long-yun-rui
     */
    public function upload($filename,$realPath);


    /**
     *
     * @param $file_key     // 文件key
     * @param $file_name    // 下载给用户看到的文件名
     * @return mixed
     * @User: long-yun-rui
     */
    public function download($file_key,$file_name);
}