<?php

namespace App\Http\Controllers\Home;

use App\Models\BookChapter;
use App\Models\Books;
use App\Http\Controllers\Controller;

class ChapterController extends Controller
{
    /**
     * 书本详情主页
     * @param int $bookId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View|void
     */
    public function info (int $bookId, int $chapterId) {
        if (empty($chapterId) || empty($bookId)) {
            return redirect()->to(to_route('home.wap.index'));
        }
        // 书本详情
        $bookInfo = (new Books())->getBookInfo($bookId);
        if (empty($bookInfo)) {
            return abort(404);
        }
        // 章节详情
        $chapterInfo = BookChapter::where('id', intval($chapterId))->first()->toArray();
        if (empty($chapterInfo)) {
            return redirect()->to(to_route('home.book.detaile',['id'=>$bookId]));
        } else {
            (new BookChapter())->setReadingNum($chapterId);
        }
        // 其它信息、当前页，下一页，上一页，返回目录地址，
        $data = [
            'hasFooter' => true,
            'chapter' => $chapterInfo,
            'bookinfo' => $bookInfo,
            'url_previous' => to_route('home.chapter.detaile', ['bookid'=>$bookId,'chapterid' => $chapterId]),
            'url_next' => (new BookChapter())->getPage($bookInfo['uuid'], $bookId, $chapterId, false),
            'url_first' => (new BookChapter())->getPage($bookInfo['uuid'], $bookId, $chapterId),
        ];
        return view('wap.chapter.index', $data);
    }


    /**
     * 书本目录
     * @param int $bookId
     * @param string $orderFiled
     * @param int $page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View|void
     */
    public function lists (int $bookId, $orderFiled = 'desc', $page = 1) {
        $listPage = 80;
        if (empty($bookId)) {
            return redirect()->to(to_route('home.wap.index'));
        }
        // 书本详情
        $bookInfo =  (new Books())->getBookInfo($bookId);
        if (empty($bookInfo)) {
            return abort(404);
        }
        if ($orderFiled  == 'asc') {
            $orderFiled = 'asc';
        }
        $chapterData = BookChapter::where('book_uuid', $bookInfo['uuid'])->orderBy('id', $orderFiled)->take($listPage)->pluck('title','id')->toArray();
        $data = [
            'bookinfo' => $bookInfo,
            'chapterList' => $chapterData,
            'orderName' => $orderFiled == 'desc' ? '倒序' : '升序',
            'orderFiled' => $orderFiled,
            'total' =>  ceil(BookChapter::where('book_uuid', $bookInfo['uuid'])->count() / $listPage)
        ];
        return view('wap.chapter.lists', $data);
    }
}
