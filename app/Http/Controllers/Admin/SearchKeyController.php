<?php

namespace App\Http\Controllers\Admin;

use App\Models\SearchKey;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchKeyController extends Controller
{
    public function lists(Request $request)
    {
        $keyword = $request->get('keyword');
        $lists = SearchKey::select([
            \DB::raw('count(*) total'),\DB::raw('GROUP_CONCAT(scws_str) as scws_str'),
            'object_type',
            'keyword'
        ])->where(function($query) use($keyword){
            $query->where('keyword','like',"{$keyword}%");
        })->groupBy('keyword')->orderBy('total','desc')->orderBy('id','desc')->paginate();
        $lists->appends(['keyword'=>$keyword]);
        return view('admin.search_key.lists',compact('lists'));
    }

}