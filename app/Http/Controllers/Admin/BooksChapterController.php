<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\BooksRequest;
use App\Models\BookChapter;
use App\Models\Books;
use App\Models\BookType;
use Beta\B;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Throwable;

class BooksChapterController extends Controller
{
    /**
     * 小说列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lists($bookUuid, Request $request){
        if (empty($bookUuid)) {
            return redirect()->to(to_route('admin.books.list'));
        }
        $bookInfo = Books::where('uuid', $bookUuid)->select('uuid', 'title', 'author', 'profiles', 'update_fild', 'update_time', 'book_cover', 'book_type','type_id')->first();
        if (empty($bookInfo)) {
            \Log::error('未找到书本信息.'. $bookUuid);
            return  redirect()->to(to_route('admin.books.list'));
        }
        $bookChapterData = BookChapter::where('book_uuid', $bookUuid)->select('id','title','create_time', 'reading_num', 'url')->paginate(100);
        $data = [
            'chapter' => $bookChapterData,
            'bookinfo' => $bookInfo,
        ];
        return view('admin.books_chapter.index',$data);
    }

    /**
     * 创建页面
     * @return \Illuminate\Http\Response
     */
    public function create($bookUuid)
    {
        if (empty($bookUuid)) {
            return redirect()->to(to_route('admin.books.lists'));
        }
        $bookInfo = Books::where('uuid', $bookUuid)->select('uuid', 'title', 'author', 'profiles', 'update_fild', 'update_time', 'book_cover', 'book_type','type_id')->first();
        if (empty($bookInfo)) {
            return redirect()->to(to_route('admin.books.lists'));
        }
        $data = [
            'bookinfo' => $bookInfo,
        ];
        return view('admin.books_chapter.edit', $data);
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
        $chapterLastInfo = BookChapter::where('id', $id)->first();
        if (empty($chapterLastInfo)) {
            return redirect()->to(to_route('admin.books.lists'));
        }
        $bookInfo = Books::where([
            ['uuid','=',$chapterLastInfo->book_uuid],
        ])->first();
        $data = [
            'bookinfo' => $bookInfo,
            'info' => $chapterLastInfo
        ];
        return view('admin.books_chapter.edit', $data);
    }

    /**保存
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        try{
            $saveData = $request->only('title', 'book_uuid', 'url','reading_num', 'content');
            return (new BookChapter())->saveData($saveData, $request->get('id') ?? 0);
        }catch (\Throwable $e){
            \Log::error($e->getMessage());
        }
        return response_error();
    }

    /**
     * 删除
     * @return \Illuminate\Http\Response
     */
    public function operation(Request $request)
    {
        try {
            $requestData = $request->only('id');
            if (empty($requestData['id'])) {
                return response_error('请求异常，操作传递错误');
            }
            if (Books::where('id', intval($requestData['id']))->update(['status' => intval($requestData['status'])])) {
                response_message('操作成功');
            }
            return response_error();
        } catch (Throwable $e) {
            \Log::warning($e->getMessage());
        }
        return response_error();
    }
}
