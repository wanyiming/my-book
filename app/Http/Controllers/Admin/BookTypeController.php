<?php

namespace App\Http\Controllers\Admin;

use App\Models\BookType;
use Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Ramsey\Uuid\Uuid;

class BookTypeController extends Controller
{

    /**
     * 分类类目lists
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lists(){
        return view('admin.book_type.lists',['bookType' => BookType::where('status', '<>', 99)->get()]);
    }

    /**
     * 添加页面显示
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(Request $request){
        $parentId = $request->request->getDigits('parent_id',0);
        $parentName = '根级类目';
        if ($parentId) {
            $parentName = BookType::where('id', $parentId)->value('name') ?? $parentName;
        }
        return view('admin.book_type.edit',compact('parentName','parentId'));
    }

    /**
     * 修改页面显示
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id){
        $typeInfo = BookType::where('id',$id)->first();
        if (empty($typeInfo)) {
            abort(404,'参数错误');
        }
        $parentId = $typeInfo['parent_id'];
        $parentName = BookType::where('id',$parentId)->value('name');
        if (is_null($parentName) && ($parentId != 0)) {
            abort(500,'父级类目不存在');
        }
        if ($parentId == 0) {
            $parentName = '根级类目';
        }
        return view('admin.book_type.edit',compact('parentName','parentId','typeInfo'));
    }

    /**
     * 删除（物理）
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function del($id){
        $count = BookType::where('parent_id',$id)->count();
        if (!empty($count)) {
            return response_error('请删除子类目，在操作');
        }
        BookType::where('id',$id)->update(['status' => 99]);

        return response_success();
    }

    /**
     * 添加 or  修改
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request){
        try{
            $params = $request->only('parent_id','name','status');
            if (!$parent = BookType::where('id',$params['parent_id'])->first()) {
                $params['parent_id'] = 0;
            }
            $params = [
                'parent_id' => intval($params['parent_id']),
                'name'  => strval($params['name']),
                'status' => intval($params['status']),
            ];
            if (empty($params['name'])) {
                return response_error('名称不能为空');
            }
            if ($id = $request->get('id')) {
                $bookTypeInfo = BookType::where('id',$id)->first();
                if (empty($bookTypeInfo)) {
                    return response_error('该分类类目不存在');
                }
                $bookTypeInfo['name'] = $params['name'];
                $bookTypeInfo['status'] = $params['status'];
                $bookTypeInfo->save();
            }else{
                $params['key'] = Uuid::uuid4();
                BookType::insertGetId($params);
            }
            return response_success();
        }catch (\Throwable $e){
            Log::warning($e);
        }
        return response_error('保存失败');
    }
}
