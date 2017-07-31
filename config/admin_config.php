<?php
/**
 * Created by PhpStorm.
 * User: lyr
 * Date: 2016/8/24
 * Time: 13:55
 */
return [
    'SITE_TITLE'=>'爱书窝管理后台 V 2.0',
    'SESSION_ADMIN_PREFIX' => '2shuwo',
    'MAX_ERROR_NUM' => 1,       //最大错误次数
    'ERROR_DIFF_TIME' => 5,     //连续错误上限时间 两者和到一起就是：ERROR_DIFF_TIME 秒内 只能错误 MAX_ERROR_NUM 次
    'SESSION_USER_PREFIX' => 'user_session_result',  //前台用户session前缀
    'GET_MESSAGE'=>1,            //如果后台提示被禁止后是否弹框提示信息
    'AFTER_SALE_END_TIME' => 7*3600*24,
    'allow_url' => [
        'http://spider.server.com'
    ]
];