<?php

namespace App\Http\Controllers\Member;

use App\Facades\SEO;
use App\Http\Controllers\Controller;
use App\Libraries\SSO\Exception;
use App\Models\Goods;
use App\Models\GoodsSale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 *
 *  厂家产品列表
 *  厂家/
 * Class ManufactorController
 * @package App\Http\Controllers\Dealer
 */
class ManufactorController extends Controller
{

    /**
     * 所有厂家产品列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function goodsLists (Request $request) {
        SEO::setTitle('厂家商品列表');
        $goodsData = (new Goods())->getManufactorGoodsLists($request, User::MANUFACTOR);
        return view('member.manufactor.goods_lists',$goodsData);
    }

    /**
     * 商品详情
     * @param int $saleId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail(int $saleId) {
        try {
            $goodsDetails  = (new GoodsSale())->getSaleDetalis($saleId);
            if (empty($goodsDetails['details'])) {
                throw  new Exception('该商品已下架！！！');
            }
            // 得到改厂家入驻的商品类目
            $goodsDetails = array_merge($goodsDetails,(new \App\Http\Controllers\Dealer\ManufactorController())->getShopDetailsInfo($goodsDetails['details']->shopInfo->user_uuid));

            SEO::setTitle($goodsDetails['details']->title.'-商品详情');

            return view('member.manufactor.goods_detail',$goodsDetails);
        } catch (\Exception $exception) {
            \Log::warning($exception->getMessage());
        } catch (\Throwable $exception) {
            \Log::warning($exception->getMessage());
        }
        return view('member.manufactor.goods_detail',['error' => $exception->getMessage()]);
    }

    /**
     * 厂商列表
     * @author: liufangyuan
     * @date: 2016-12-8
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function firmLists(Request $request)
    {
        $where = $request->all();
        \SEO::setTitle('厂商列表');
        $lists = DB::table('firm_and_dealer')->where('seller_type',User::MANUFACTOR)->whereIn('status',[User::STATUS_ON,User::STATUS_VER])->where(function($query) use($where){//公司名称筛选
            if (!empty($where['c'])){
                $query->where('shop_name','like','%'.$where['c'].'%');
            }
        })->where(function($query) use($where){//地区筛选
            if (!empty($where['a'])){
                $query->where('area','like','%,'.$where['a']);
                $query->orWhere('area','like','%,'.$where['a'].',%');
                $query->orWhere('area','like',$where['a'].',%');
            }
        })->paginate();
        return view('member.manufactor.firmLists',compact('lists','where'));
    }

    /**
     * 厂家介绍
     * @author: liufangyuan
     * @date: 2016-12-8
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function firmShow(Request $request)
    {
        try{
            $uuid = (string)$request->get('uuid');
            if (empty($uuid)) {
                return view('member.firm.lists');
            }

            // 得到改厂家入驻的商品类目
            $goodsDetails = (new \App\Http\Controllers\Dealer\ManufactorController())->getShopDetailsInfo($uuid);
            \SEO::setTitle($goodsDetails['info']->shop_name.'-经销商介绍');
            return view('member.manufactor.firmShow',$goodsDetails);
        }catch (\Exception $e){
            Log::error($e);
            return redirect()->to(to_route('member.firm.lists'));
        }

    }

    /**
     * 店铺的所有产品
     * @param Request $request
     * @param $shopId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function shopGoods (Request $request, string $shopUUID) {
        $request->query->set('shopuuid', $shopUUID);
        $goodsData = (new Goods())->getManufactorGoodsLists($request, User::MANUFACTOR);
        $goodsData = array_merge($goodsData, (new \App\Http\Controllers\Dealer\ManufactorController())->getShopDetailsInfo($shopUUID));
        SEO::setTitle($goodsData['info']->shop_name.'-商品列表');
        return view('member.manufactor.shop_goods',$goodsData);
    }
}
