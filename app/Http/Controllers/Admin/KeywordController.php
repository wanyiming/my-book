<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SysKeywordRequest;
use App\Models\SysKeyword;
use Illuminate\Http\Request;

/**
 * 关键词
 * Class KeywordController
 * @package App\Http\Controllers\Admin
 */
class KeywordController extends Controller
{

    /**
     * 搜索关键词管理
     * @author:wym
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lists (Request $request) {
        $param = $request->only('name','status');
        $lists = (new SysKeyword())->where(function($query) use ($param){
            if ($param['name']) {
                $query->where('name','like','%'.htmlspecialchars(trim($param['name'])).'%');
            }
        })->where(function($query) use ($param){
            if ($param['status']) {
                $query->where('status',intval($param['status']));
            }
        })->orderBy('id','desc')->paginate(SysKeyword::PAGE_NUM);
        return view('admin.keyword.lists',['data'=>$lists,'where'=>$param,'status'=>SysKeyword::STATUS_ARR]);
    }

    /**
     * 敏感词编辑页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request) {
        $id = $request->get('id');
        $info = [];
        if (!empty($id)) {
            $info = SysKeyword::where('id',intval($id))->first();
        }
        return view('admin.keyword.edit',['info'=>$info]);
    }

    /**
     * @param SysKeywordRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(SysKeywordRequest $request) {
        try {
            $data = $request->only('name','url','weight','status');
            $data['name'] = htmlspecialchars(trim($data['name']));
            $data['url'] = htmlspecialchars(trim($data['url']));
            $data['weight'] = intval($data['weight']);
            $data['status'] = $data['status'] ?? 2;
            if (intval($request->get('id')) > 0) {
                $tab = SysKeyword::where('id',intval($request->get('id')))->update($data);
            } else {
                $tab =SysKeyword::insertGetId($data);
            }
            if (empty($tab)) {
                return redirect()->back()->with('error', '关键词信息保存失败!');
            }
            return redirect(url('admin/keyword/index'))->withSuccess('保存信息成功!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * 关键词状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editStatus (Request $request) {
        try {
            $param = $request->only('id','status');
            if (empty($param['id'])) {
                return response_error('请求参数错误');
            }
            if (SysKeyword::where('id',intval($param['id']))->update(['status'=>intval($param['status'])]) ) {
                return response_success('操作成功！',200);
            }
            return response_error('操作失败');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response_error('操作失败');
        }
    }


    /**
     * 跟新缓存
     */
    public function clear() {
        \Cache::forget('catchKeyword');
        return redirect()->back()->withSuccess('更新缓存成功!');
    }
}
