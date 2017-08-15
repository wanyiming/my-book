<?php

namespace App\Http\Controllers\Home;

use App\Models\BookChapter;
use App\Models\Books;
use App\Models\BookType;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller
{

    private static $listType = [
        'click' => ['filed' => 'read_num', 'file_name' => '点击榜'],
        'update' => ['filed' =>'update_time', 'file_name' => '更新榜'],
        'recommend' => ['filed' =>'recom_num', 'file_name' => '推荐榜'],
        'collect' =>['filed' => 'recoll_num', 'file_name' => '收藏榜'],
        'all' => ['filed' =>'id', 'file_name' => '点击榜'],
    ];

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


    // 书本推荐列表
    public function lists ($typeSEO = 'all', $page = '1') {
        $pageRows = 40;
        if ($page > 10) {
            $page = rand(1,10);
        }
        if (!in_array($typeSEO, array_keys(self::$listType))) {
            return redirect()->to(to_route('/'));
        }
        $result = (new Books())->orderData('all', self::$listType[$typeSEO]['filed'], $pageRows, $page);
        $totalCount = '10';//(new Books())->orderDataTotal('all');
        $urlPrevious = $_SERVER['REQUEST_URI'];
        $data = [
            'data' => $result,
            'filedStr' =>self::$listType[$typeSEO],
            'total' =>  $totalCount,
            'page' => $page,
            'url_prev' => $page <= 1 ? to_route('home.book.sort',['seo' => $typeSEO,'page'=>1]) : str_replace('list_'.$page, 'list_'.($page - 1), $urlPrevious),
            'url_next' => $page >= $totalCount ?  to_route('home.book.sort',['seo' => $typeSEO,'page'=>$totalCount])  : str_replace('list_'.$page, 'list_'.($page + 1), $urlPrevious),
        ];
        return view('wap.book.lists', $data);
    }
}
