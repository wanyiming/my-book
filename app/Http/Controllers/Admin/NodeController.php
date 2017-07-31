<?php

namespace App\Http\Controllers\Admin;

use App\Models\SysNode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpParser\Node;
use Throwable;
use Log;
use DB;

/**
 * 后台节点管理
 *
 * Class NodeController
 * @package App\Http\Controllers\Admin
 * @author dch
 */
class NodeController extends Controller
{
    public function search(Request $request){
        $routeTitle = $request->get('name');
        $routeName = SysNode::where('title',$routeTitle)->value('route');
        $url = to_route($routeName);
        return redirect(empty($url) ? route('admin.index') : $url);
    }

    public function lists()
    {
        $nodes = SysNode::all();
        $parentId = 0;

        return view('admin.node.lists',compact('nodes','parentId'));
    }

    public function destroy($id)
    {
        $subCount = SysNode::where('parent_id',$id)->count();
        if(!empty($subCount)){
            return response_error('请先删除子节点,再进行操作');
        }

        SysNode::where('id',$id)->delete();

        return response_success();
    }

    public function edit($id)
    {
        $node = SysNode::where('id',$id)->first();
        if(empty($node)){
            abort(404,'参数错误');
        }
        $parentId = $node['parent_id'];
        $parentName = SysNode::where('id',$parentId)->value('title');
        if(is_null($parentName) && ($parentId != 0)){
            abort(500,'父级不存在');
        }
        if($parentId == 0){
            $parentName = '根级节点';
        }
        return view('admin.node.edit',compact('parentName','parentId','node'));
    }

    public function add(Request $request)
    {
        $parentId = $request->request->getDigits('parent_id',0);
        $parentName = '根级节点';
        if($parentId){
            $parentName = SysNode::where('id',$parentId)->value('title') ?? $parentName;
        }

        return view('admin.node.edit',compact('parentName','parentId'));
    }

    public function save(Request $request)
    {
        try {
            $params = $request->only('parent_id', 'title', 'ico', 'is_show', 'route');

            if (!$parent = SysNode::where('id', $params['parent_id'])->first()) {
                $params['parent_id'] = 0;
            }

            $params['parent_id'] = intval($params['parent_id']);
            $params['is_show'] = intval(boolval($params['is_show']));
            $params['route'] = strval($params['route']);
            $params['ico'] = strval($params['ico']);
            $params['title'] = strval($params['title']);
            $params['sort'] = 99;

            if(empty($params['title'])){
                return response_error('节点名不能为空');
            }
            if(substr_count($parent['tree'],',') >= 3){
                return response_error('最多只能添加四级');
            }
            if($id = $request->get('id')){
                $node = SysNode::where('id',$id)->first();
                if(empty($node)){
                    return response_error('节点不存在');
                }
                $node['title'] = $params['title'];
                $node['is_show'] = $params['is_show'];
                $node['route'] = $params['route'];
                $node['ico'] = $params['ico'];
                $node->save();
            }else{
                DB::beginTransaction();
                $id = SysNode::insertGetId($params);
                SysNode::where('id', $id)->update(['tree' => implode(',',array_filter([$parent['tree'], $id]))]);
                DB::commit();
            }


            return response_success();
        } catch (Throwable $e) {
            Log::warning($e);
        }

        return response_error('保存失败');
    }
}
