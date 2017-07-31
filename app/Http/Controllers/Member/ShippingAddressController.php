<?php

namespace App\Http\Controllers\Member;

use App\Http\Requests\ShippingAddressRequest;
use App\Models\ShippingAddess;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * 收货地址
 * @author  liufangyuan
 * Class ShippingAddressController
 * @package App\Http\Controllers\Member
 */
class ShippingAddressController extends Controller
{
    /**
     * 获取设置收货地址页面
     * @author: liufangyuan
     * @date: 2016-12-8
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $id = (int)$request->get('id');
        //获取当前登录用户的所有收货地址
        $lists = ShippingAddess::where('user_uuid',get_user_session_info('user_uuid'))->where('status','!=',ShippingAddess::STATUS_DEL)
            ->orderBy('is_default','DESC')->orderBy('created_at','DESC')->get();
        $info = false;
        if (!empty($id)){
            //验证数据是否存在
            $info = ShippingAddess::where('id',$id)->where('user_uuid',get_user_session_info('user_uuid'))->first();
        }
        \SEO::setTitle('收货地址');
        return view('member.shippingAddress.index',compact('lists','info'));
    }

    /**
     * 保存收货地址
     * @author: liufangyuan
     * @date: 2016-12-8
     */
    public function addressSave(ShippingAddressRequest $shippingAddressRequest)
    {
        $data = $shippingAddressRequest->only('id','receiver','area','address','code','mobile','area_code','phone','is_default','_token');
        $result = ShippingAddess::shippingSave($data);
        return $result;
    }

    /**
     * 删除收货地址
     * @author: liufangyuan
     * @date: 2016-12-8
     */
    public function addressDel(ShippingAddess $shippingAddess,Request $request)
    {
        $id = (int)$request->get('id');
        return $shippingAddess::addressSave($id);
    }

    /**
     * 设置默认地址
     * @author: liufangyuan
     * @date: 2016-12-8
     * @param ShippingAddess $shippingAddess
     * @param Request $request
     * @return string
     */
    public function setDefault(ShippingAddess $shippingAddess,Request $request)
    {
        $id = (int)$request->get('id');
        return $shippingAddess::setDefault($id);
    }
}
