<?php

namespace App\Http\Controllers\Admin;

use App\Models\Recommend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * 推荐分类
 * author ：wym
 * Class RecommendController
 * @package App\Http\Controllers\Admin
 */
class RecommendController extends Controller
{
    public function __construct()
    {
    }

    /**
     * 修改添加页面
     * @param $objType
     * @param $objId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit ($objType, $objId) {
        $list = (new Recommend())->getRecommendData($objType, $objId);
        return view('admin.recommend.edit',['data'=>$list->toArray(),'object_position'=>$objType == Recommend::OBJECT_TYPE_FRIENDLY ? Recommend::FRIENDLY_ATT : Recommend::OBJECT_POSITION_ARRAY,'objtype'=>$objType,'objid'=>$objId]);
    }

    /**
     * 保存提交的信息
     * @param Request $request
     */
    public function save (Request $request) {
        $param = $request->only('objtype','objid','object_position','begin_at','end_at','weight','object_remark');
        if (empty($param['objtype']) || empty($param['objid'])) {
            return response_error('请求参数错误');
        }
        try {
           return Recommend::edit($param);
        } catch (\Exception $e) {
            return response_error($e->getMessage());
        }
    }
}
