<?php

namespace App\Http\Controllers\Member;

use App\Models\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * 操作中心，收藏，推荐
 * @author  liufangyuan
 * Class CollectionController
 * @package App\Http\Controllers\Member
 */
class OperationController extends Controller
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
     * 添加收藏
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function hander (Request $request) {
        try {
            $probjectData = $request->only('bookid', 'type');
            print_r($probjectData); die;
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
