<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    // 首页
    public function index (Request $request) {
        return view('home.news.index');
    }
}
