## SEO 的使用说明
```
# File:Http/Controllers/Home/YouController.php
<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use SEO;

class YouController extends Controller
{
    // 详情页举例
    public function detail()
    {    
        // 简单粗暴
        SEO::setTitle('这是详情页的标题');
        SEO::setKeywords(['关键词一','关键词二']); // 等同于 SEO::setKeywords('关键词一','关键词二')
        SEO::setDescription('这是详情的描述');
        
        // 或者使用SEO规则模板(数据表:pub_seo_rule)
        
        SEO::setRule('YOU_DETAIL'); // 设置规则,使用哪条规则
        SEO::setSitename('问问我'); // 给变量为 {sitename} 赋值,等同于 SEO::setVariable('sitename','问问我')
        //批量赋值
        SEO::setVariables(['urlname'=>'wenwen.wo','cityname'=>'重庆']);
        
        return view('home.you.detail');
    }
}
```
```
模板里面直接调用
{!! SEO::generate() !!}
```