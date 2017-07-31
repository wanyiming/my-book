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

class IndexController extends Controller
{

    // 网站首页
    public function index () {
        $data = [
            'rec_data' => (new Books())->bookData(3),
            'upt_data' => (new Books())->orderData(),
        ];
        return view('wap.news.index', $data);
    }
}