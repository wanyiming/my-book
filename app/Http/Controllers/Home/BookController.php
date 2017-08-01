<?php

namespace App\Http\Controllers\Home;

use App\Models\BookChapter;
use App\Models\Books;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller
{
    /**
     * 书本详情主页
     * @param int $bookId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View|void
     */
    public function index (int $bookId) {
        if (empty($bookId)) {
            return redirect()->to(to_route('home.wap.index'));
        }
        // 书本详情
        $bookInfo =(new Books())->getBookInfo($bookId);
        if (empty($bookInfo)) {
            return abort(404);
        } else {
            (new Books())->setReadingNum($bookId);
        }
        // 书本开始和结束10章章节信息；
        $bookChapterData['newData'] = (new BookChapter())->getBookChapter($bookInfo['uuid']);
        $bookChapterData['firstData'] = (new BookChapter())->getBookChapter($bookInfo['uuid'], 'id', 'asc');
        // 最近书评
        $commendData = (new Comment())->bookComment($bookInfo['uuid']);
        return view('wap.book.index', ['bookinfo' => $bookInfo, 'chapter' => $bookChapterData, 'comment' => $commendData]);
    }

}
