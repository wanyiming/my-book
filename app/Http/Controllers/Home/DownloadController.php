<?php

namespace App\Http\Controllers\Home;

use App\Libraries\Tab\QiNiuUpload;
use App\Libraries\Tab\TabUpload;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DownloadController extends Controller
{
    public function download($file_id){
        // 生成依赖（使用七牛云上传,切换到本地上传时，请将.env文件中的RESOURCE_DOMAIN改为空，这里其实可以改成自动的，但是时间不够，将就用吧）
        $downloadMethod =  new QiNiuUpload();
        //$uploadMethod =  new LocalUpload();
        // 注入依赖（手动注入已经够用了吧？）
        $downloadModel = new TabUpload( $downloadMethod );
        $downloadModel->downloadAttachment($file_id);
    }
}
