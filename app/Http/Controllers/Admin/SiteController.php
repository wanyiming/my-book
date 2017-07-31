<?php

namespace App\Http\Controllers\Admin;

class SiteController extends CommonController
{
    function __construct()
    {
        parent::__construct();
    }

    public function index(){
        return view('admin.index.index');
    }
}
