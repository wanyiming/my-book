<?php

namespace App\Http\Controllers\Home;


use App\Facades\SEO;
use App\Http\Controllers\Controller;
use App\Models\FriendlyLink;
use App\Models\Information;
use App\Models\InformationContent;
use App\Models\Recommend;

class ProtocolInformationController extends Controller
{

    function __construct()
    {

    }

    /**
     * 协议
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id = 0){
        $list = Information::where('status',FriendlyLink::LINK_STATUS_SUCCESS)->select('information_name','information_img','id')->get()->toArray(); // 咨询
        $list = array_pluck($list, null, 'id');
        if (empty($id)) {
            $id = array_keys($list)[0];
        }
        $content = InformationContent::where('information_id',$id)->first(); // 咨询内容
        if (empty($content)) {
            $content['title']  = $list[$id]['information_name'];
            $content['conent'] = "没有内容哦！请联系管理员";
        }
        SEO::setTitle($content['title']. ' - 问问我建材商城');
        $active = ['id'=> $id, 'name' => $list[$id]['information_name'],'img' => $list[$id]['information_img']];
        $friendly_type = Recommend::FRIENDLY_XIEYI;
        return view('home.protocol_information.information',compact('list','content','active','friendly_type'));
    }

    /**
     * 友情链接
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function link(){
        \SEO::setTitle('友情链接 - 问问我建材商城');
        $list = Information::where('status',FriendlyLink::LINK_STATUS_SUCCESS)->orderBy('id','desc')->pluck('information_name','id')->toArray(); // 得到咨询分类
        $links = FriendlyLink::friendlyData(Recommend::FRIENDLY_XIEYI);
        $friendly_type = Recommend::FRIENDLY_XIEYI;
        return view('home.protocol_information.link',compact('list','links', 'friendly_type'));
    }

}
