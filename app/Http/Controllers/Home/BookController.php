<?php

namespace App\Http\Controllers\Home;

use App\Models\Books;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller
{
    // 首页
    public function index (int $bookId) {
        if (empty($bookId)) {
            return redirect()->to(to_route('home.wap.index'));
        }
        // 书本详情
        $bookInfo = Books::where('id', intval($bookId))->first()->toArray();
        if (!empty($bookInfo)) {
            return abort(404);
        }
        // 书本开始和结束10章章节信息；

        // 最近书评

        return view('wap.book.index');
    }
}
