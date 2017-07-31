<?php

namespace App\Http\Controllers\Member;

use App\Models\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 关注中心
 * @author  liufangyuan
 * Class CollectionController
 * @package App\Http\Controllers\Member
 */
class CollectionController extends Controller
{
    /**
     * 店铺收藏
     * @author: liufangyuan
     * @date: 2016-12-8
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function shopLists()
    {

        $field = ['c.id','f.store_image','f.shop_name','f.id as shop_id','c.aims_id'];
        $lists = DB::table('collection as c')->join('firm_and_dealer as f','f.id','=','c.aims_id')->where('c.user_uuid',get_user_session_info('user_uuid'))->orderBy('c.created_at','DESC')->select($field)->paginate(12);
        $dataItem = $lists->toArray();
        \SEO::setTitle('店铺收藏');
        return view('member.collection.shop',compact('lists','dataItem'));
    }

    /**
     * 商品收藏
     * @author: liufangyuan
     * @date: 2016-12-8
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function goodsLists()
    {
        $field = ['c.id','g.cover_file_id','g.title','c.aims_id'];
        $lists = DB::table('collection as c')->join('goods as g','g.id','=','c.aims_id')->where('c.user_uuid',get_user_session_info('user_uuid'))->orderBy('c.created_at','DESC')->select($field)->paginate(12);
        $dataItem = $lists->toArray();
        \SEO::setTitle('产品收藏');
        return view('member.collection.goods',compact('lists','dataItem'));
    }

    /**
     * 取消关注
     * @author: liufangyuan
     * @date: 2016-12-8
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel(Request $request)
    {
        $id = (array)$request->get('id');
        if (empty($id) || !is_array($id)){
            return response_error('参数错误');
        }
        try{
            //验证数据是否存在
            $checkData = Collection::where('user_uuid',get_user_session_info('user_uuid'))->whereIn('id',$id)->first();
            if (!$checkData){
                return response_error('很抱歉，数据信息不存在');
            }
            //验证通过,更新数据
            $result = Collection::whereIn('id',$id)->where('user_uuid',get_user_session_info('user_uuid'))->delete();

            if($result){
                return response_message('取消关注成功');
            }else {
                return response_error('取消关注失败');
            }
        }catch (\Exception $e){
            Log::error($e);
            return response_error('系统繁忙，请稍后再试');
        }
    }

    /**
     * 添加收藏
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function objectAdd (Request $request) {
        try {
            $collectionParam = $request->only('aims_id', 'type');
            if (empty($collectionParam['aims_id'])) {
                return response_error('关注失败，请求参数信息有误', Collection::COLLECTION_FAIL);
            }
            $henderState = (new Collection())->addCollection(intval($collectionParam['aims_id']),$collectionParam['type']);
            if ($henderState === Collection::HAS_COLLECTION_STATE) {
                return response_message('您已关注，请勿重复关注', Collection::HAS_COLLECTION_STATE);
            }
            if ($henderState === false) {
                return response_error('关注失败', Collection::COLLECTION_FAIL);
            }
            return response_success('', '关注成功');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
        return response_success('关注失败', Collection::COLLECTION_FAIL);
    }
}
