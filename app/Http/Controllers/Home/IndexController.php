<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/28
 * Time: 16:10
 */

namespace App\Http\Controllers\Home;


use App\Http\Controllers\Controller;
use App\Models\Books;
use App\Models\BookType;

class IndexController extends Controller
{
	
    // 网站首页
    public function index () {
        // 所有分类
        $data = [
            'rec_data' => (new Books())->bookData(3),
            'upt_data' => (new Books())->orderData(),
			'click_data' => (new Books())->orderData('all', 'read_num'),
			'recomment_data' => (new Books())->orderData('all', 'recom_num'),
			'collection_data' => (new Books())->orderData('all', 'recoll_num',5),
            'book_types' => array_pluck(BookType::all(),null, 'key')
        ];
        return view('wap.news.index', $data);
    }
}