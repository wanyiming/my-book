<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\BooksRequest;
use App\Models\Books;
use App\Models\BookType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Throwable;

class BooksController extends Controller
{
    /**
     * 小说列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lists(Request $request){

        $param = $request->only('book_type', 'status', 'type_id', 'author', 'title');

        $lists = Books::where(function ($query) use ($param){
            if ($param['book_type']) {
                $query->where('book_type', htmlentities($param['book_type']));
            }
        })->where(function ($query) use ($param) {
            if ($param['status']) {
                $query->where('status', intval($param['status']));
            }
        })->where(function ($query) use ($param) {
            if ($param['type_id']) {
                $query->where('type_id', intval($param['type_id']));
            }
        })->where(function ($query) use ($param) {
            if ($param['author']) {
                $query->where('author', 'like', '%'.htmlentities(trim($param['author'])).'%');
            }
            if ($param['title']) {
                $query->where('title', 'like', '%'.htmlentities(trim($param['title'])).'%');
            }
        })->orderBy('id', 'desc')->paginate();
        $lists->appends($param);
        $data = [
            'lists' => $lists,
            'types' => BookType::pluck('name','key')->toArray(),
            'status_' => Books::STATUS_ALL,
            'type_' => Books::TYPES_ALL
        ];
        return view('admin.books.index',$data);
    }

    /**
     * 创建页面
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.books.edit',['bookType' => BookType::where('status', Books::STATUS_ON)->pluck('name','key')->toArray()]);
    }

    /**
     * 展示编辑页面
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (empty($id)) {
            return abort('405');
        }
        $info = Books::where([
            ['id','=',$id],
        ])->firstOrFail();
        return view('admin.books.edit',['info'=>$info,'bookType' => BookType::where('status', Books::STATUS_ON)->pluck('name','key')->toArray()]);
    }

    /**保存
     * @param BooksRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Symfony\Component\HttpFoundation\Response
     */
    public function store(BooksRequest $request)
    {
        try{
            $saveData = $request->only('title', 'author', 'url', 'book_type','font_size', 'book_cover', 'type_id', 'status', 'update_fild', 'update_time', 'recom_num', 'read_num', 'profiles');
            return (new Books())->saveBook($saveData, $request->get('id') ?? 0);
        }catch (\Throwable $e){
            \Log::error($e->getMessage());
        }
        return response_error();
    }
    /**
     * 删除资讯
     * @return \Illuminate\Http\Response
     */
    public function operation(Request $request)
    {
        try {
            $requestData = $request->only('status', 'id');
            if (empty($requestData['status']) || empty($requestData['id'])) {
                return response_error('请求异常，操作传递错误');
            }
            if (Books::where('id', intval($requestData['id']))->update(['status' => intval($requestData['status'])])) {
                return response_message('操作成功');
            }
            return response_error();
        } catch (Throwable $e) {
            \Log::warning($e->getMessage());
        }
        return response_error();
    }
}
