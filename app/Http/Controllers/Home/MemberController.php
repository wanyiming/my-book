<?php

namespace App\Http\Controllers\Home;

use App\Models\JoinApply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MemberController extends Controller
{
    //
    public function index(){
        $type = JoinApply::JOIN_APPLY_TYPE_MEMBER;
        $memberClass = 'active';
        \SEO::setRule('JCSC_MEMBER');
        return view('home.member.index',compact('type','memberClass'));
    }
}
