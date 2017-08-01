<?php

namespace App\Http\Controllers\Home;

use App\Models\BookChapter;
use App\Models\Books;
use App\Models\Comment;
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
        $bookInfo = Books::where('id', intval($bookId))->where('status', Books::STATUS_ON)->first()->toArray();
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


        return view('wap.chapter.index', ['hasFooter' => true, 'chapter' => $chapterInfo]);
    }

}
