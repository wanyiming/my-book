<?php

namespace App\Http\Controllers\Home;

use App\Models\BookChapter;
use App\Models\Books;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

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
        $chapterData = BookChapter::where('book_uuid', $bookInfo['uuid'])->orderBy('id', $orderFiled)->forPage($page, $listPage)->pluck('title','id')->toArray();

        // 总页数
        $totalCount = ceil(BookChapter::where('book_uuid', $bookInfo['uuid'])->count() / $listPage);

        if ($page > $totalCount) {
            return redirect()->to(to_route('home.book.detaile',['id' => $bookId]));
        }
        $urlPrevious = $_SERVER['REQUEST_URI'];
        $data = [
            'bookinfo' => $bookInfo,
            'chapterList' => $chapterData,
            'orderName' => $orderFiled == 'desc' ? '倒序' : '升序',
            'orderFiled' => $orderFiled,
            'total' =>  $totalCount,
            'page' => $page,
            'url_prev' => $page <= 1 ? to_route('home.chapter.lists',['bookid' => $bookId, 'order' => $orderFiled,'page'=>1]) : str_replace($page.'.html', ($page - 1) . '.html', $urlPrevious),
            'url_next' => $page >= $totalCount ?  to_route('home.chapter.lists',['bookid' => $bookId, 'order' => $orderFiled,'page'=>$totalCount])  : str_replace($page.'.html', ($page + 1) . '.html', $urlPrevious),
        ];
        return view('wap.chapter.lists', $data);
    }


    /**
     * 评价列表
     * @param $bookId
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View|void
     */
    public function comment ($bookId,Request $request) {
        if (empty($bookId)) {
            return redirect()->to(to_route('home.wap.index'));
        }
        $bookInfo =  (new Books())->getBookInfo($bookId);
        if (empty($bookInfo)) {
            return abort(404);
        }
        $commendList = Comment::where('status', Comment::STATUS_NO)->where('book_uuid', $bookInfo['uuid'])->paginate(1);
        $data = [
            'bookinfo' => $bookInfo,
            'comment' => $commendList
        ];
        return view('wap.chapter.comment', $data);
    }
}
