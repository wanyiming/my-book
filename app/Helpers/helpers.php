<?php

if (!function_exists('send_messign')) {
    /**
     * 发送短信码
     *
     * @param $mobile 手机号
     * @param $content 发送内容； 可以为默认普通验证短信； 如果要手动传入发送内容例如： “您再平台认证的店铺，你的手机验证码是{{code}}，请不要告诉他人”；  {{code}}, 替换发送验证码
     * @return bool
     */
    function send_messign($mobile,$type = 1 ,$content = '')
    {
        if (empty($mobile)) {
            return false;
        }
        require_once  __DIR__."/../Logic/SendMessign.php";
        return \App\Logic\SendMessignLogic::sendMessign($mobile, $type , $content);
    }
}

if (!function_exists('random_char')) {
    /**
     * 注册时所用到的注册码
     *
     * @return string
     * @author:wym
     */
    function random_char()
    {
        $char = '0123456789';
        $mak = '';
        for ($i = 0; $i < 6; $i++) {
            $mak .= $char[mt_rand(0, strlen($char) - 1)];
        }
        return $mak;
    }
}

if (!function_exists('ensitive_word_filtering')) {
    /**
     *敏感词集合
     *
     * @param string $subject 需要过滤的对象
     * @param string $replace 替换对象 文字或者是样式
     * @param boole $isCount 是否返回替换次数
     * @return array || string
     * @author:wym
     */
    function ensitive_word_filtering($subject, $replace = '***', $isCount = false)
    {
        $search = array();
        $file_path = "./static/mgck.text"; //要打开的文件的相对路径或者绝对路径
        if (file_exists($file_path)) {    //判断要打开的txt文件是否存在
            $handle = fopen($file_path, 'r');
            while (!feof($handle)) {
                $search[] = trim(fgets($handle));
            }
            fclose($handle);
        }
        if (empty($search)) {
            return $subject;
        }
        $i = 0;
        $replaceStr = [];
        if (true === $isCount) {
            $repStr = $replace == '***' ? '***' : "<label style='color:%s;fond-size:600'>%s</label>";
            foreach ($search as $k=>$v) {
                if(stripos($subject, $v) !== false ){
                    $i ++;
                    $replaceStr [] = $v;
                    $subject = str_replace($v, sprintf($repStr, $replace, $v), $subject);
                }
            }
            $subject = htmlspecialchars_decode($subject);
            return array('count' => $i, 'subject' => $subject, 'replace_str' => implode('###', array_filter(array_unique($replaceStr))));
        } else {
            $subject = str_replace($search, $replace, $subject, $i);
        }
        return htmlspecialchars_decode($subject);
    }
}

if (!function_exists('get_client_ip')) {

    /**
     * 获取客户端IP地址
     *
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     * @author:wym
     */
    function get_client_ip($type = 0,$adv=false) {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if($adv){
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    =   array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip     =   trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     =   $_SERVER['HTTP_CLIENT_IP'];
            }elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     =   $_SERVER['REMOTE_ADDR'];
            }
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }
}

//根据ip获取ip归属地
function ip2add($ip='',$type=true,$glue='-'){
    return implode($glue,array_filter(\App\Libraries\Ip\IP::find($ip)));
}

/**
 * 统一验证
 * @param $str 字符串
 * @param $t 类型
 * @return bool|int
 */
function validate($str , $t){
    static $validateArray = array(
        'e' => '/^[\w\d]+[\w\d-.]*@[\w\d-.]+\.[\w\d]{2,10}$/',//email 邮箱
        'm' => '/^1[356879]{1}[0-9]{9}$/',//mobile 手机
        'mobile' => '~^(?=\d{11}$)^1(?:3\d|4[57]|5[^4\D]|7[^249\D]|8\d)\d{8}$~',//mobile 手机
        't' => '/^0[0-9]{2,3}[-]?\d{7,8}$/',//telphone 座机
        'tel' => '/^(?:(?:0\d{2,3})-)?(?:\d{7,8})(-(?:\d{3,}))?$/',//telphone 座机
        'p' => '/^\d{6}$/',//post 邮编
        'card' => '/^([1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3})|(/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}(\d|x|X)$/)$/',//post 身份证
        'user' => '/^[a-zA-Z]{1}([a-zA-Z0-9]|[._]){5,18}$/',//用户名
        'pass' => '/^[\\~!@#$%^&*()-_=+|{}\[\],.?\/:;\'\"\d\w]{6,16}$/',//密码
        'money'=>'/(^[1-9]([0-9]+)?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/'
    );
    if (!array_key_exists($t, $validateArray)) return false;
    return preg_match($validateArray[$t], $str);
}

if (!function_exists('file_id2url')) {
    /**
     * 文件ID获取文件的URL
     *
     * @param string $fileId 文件的 UUID
     * @param bool $isFull 是否获取完整路径
     * @return string file_url 文件的访问 URL
     * @author dch
     */
    function file_id2url(string $fileId, bool $isFull = false)
    {
        if (!\Ramsey\Uuid\Uuid::isValid($fileId)) {
            return '';
        }
        $visitUrl = \App\Models\DisFile::getModel()->fileId2url($fileId);

        return $isFull ? url($visitUrl) : $visitUrl;
    }
}

if ( ! function_exists('sql_dump')) {
    /**
     * 打印原生sql语句
     * 在你想打印的sql语句之前使用此方法
     *
     */
    function sql_dump(){
        \DB::listen(function ($query) {
            $bindings = $query->bindings;
            $i = 0;
            $rawSql = preg_replace_callback('/\?/', function ($matches) use ($bindings, &$i) {
                $item = isset($bindings[$i]) ? $bindings[$i] : $matches[0];
                $i++;
                return gettype($item) == 'string' ? "'$item'" : $item;
            }, $query->sql);
            echo $rawSql."\n<br /><br />\n";
        });
    }
}


if (!function_exists('get_links_data')) {
    /**
     * 返回友情链接站点
     * @return mixed
     */
    function get_links_data($type = 0){
        return Cache::remember('linksData',\Carbon\Carbon::now()->addMinutes(60),function(){
            return \App\Models\Link::where('status',\App\Models\Link::STATUS_ENABLE)->select('title','link')->orderBy('sort','asc')->get();
        });
    }
}


if (!function_exists('error_show_msg')) {
    /**
     * 错误消息提示中间页面
     *
     * @param null $msg
     * @param null $url
     * @param int $settime
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author wym
     */
    function error_show_msg ($msg = null, $url = null, $settime = 3) {
        return response()->view('errors.sorry', ['msgData'=>['msg'=>$msg,'url'=>$url, 'time'=>$settime]]);
    }
}


if (!function_exists('success_show_msg')) {
    /**
     * 提示消息提示中间页面
     *
     * @param null $msg
     * @param null $url
     * @param int $settime
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author wym
     */
    function success_show_msg ($msg = null, $url = null, $settime = 3) {
        return response()->view('errors.success', ['msgData'=>['msg'=>$msg,'url'=>$url, 'time'=>$settime]]);
    }
}


if (!function_exists('recommend_info')) {
    /**
     * 返回推荐按钮
     *
     * @param int $objType
     * @param int $objId
     * @return string
     * @author wym
     */
    function recommend_info(int $objType, int $objId) {
        $isRecm = (new \App\Models\Recommend())->checkRecommend($objType, $objId);
        if ($isRecm === true) {
            return '<button class="btn btn-success btn-xs recommendObjectSubmit" data-id='.$objId.' data-type='.$objType.'><i class="fa fa-hand-o-up"></i> 已推荐</button>';
        }
        return '<button class="btn btn-warning btn-xs recommendObjectSubmit" data-id='.$objId.' data-type='.$objType.'><i class="fa fa-hand-o-down"></i> 未推荐</button>';
    }
}

if (!function_exists('get_sms_template')) {
    /**
     * 获取静态模板
     * @param string $callKey
     * @param array $parameter 作为替换参数使用，key的值不能为数组类型； ['tender_title' => '需求标题', 'tender_moblie' => '需求联系方式']
     * 调用方式 tender_title => '值'
     * 数据库中 {tender_title}
     * 替换的结果为  值
     * @return bool|string
     */
    function get_sms_template ($callKey = '', $parameter = []) {
        if (empty($callKey)) {
            return false;
        }
        return (new \App\Models\SmsTemplate())->getSmsTemplate($callKey, $parameter);
    }
}

if(!function_exists('filterArea')){
    /**
     * 去除区域中无用字符
     *
     * @param $areaInfo
     * @return mixed
     * @author dch
     */
    function filterArea($areaInfo)
    {
        return preg_replace('~((-县)|(-市辖区)|(-市辖区)|(-省直辖县级行政区划))~Uis','',$areaInfo);
    }
}


if (!function_exists('get_search_keyword')) {
    /**
     * 关键词配置
     * @param int $site
     * @return mixed
     */
    function get_search_keyword($site = 0) {
        return \App\Models\SysKeyword::getSysKeyword($site);
    }
}

/************************** SE0 相关方法 - end ****************************/

if (!function_exists('get_watermark_size')) {
    /**
     * 得到增加水印的比例
     * author wym
     * @param $watermarkKey 调用数据表中的key
     * @return array
     */
    function get_watermark_size ($watermarkKey = null) {
        return ['width'=>1,'height'=>1,'long_height' => 1, 'watermark_path' => app()->make('path.storage')."/app/public/watermark/watermark.png"];
    }
}

if (!function_exists('static_site_link')) {

    /**
     * 返回静态模板资源域名
     * @return string
     */
    function static_site_link() {
        return env('STATIC_SITE_LINK','http://static.jcsc.wenwenwo.com/');
    }
}

if (!function_exists("qiniu_domain")) {

    /**
     * 组装七牛图片信息
     * @param $key
     * @param $param ['w'=>,'h'=>'']
     * @return string
     */
    function qiniu_domain($key = null, $param = []) {
        if (empty($key)) {
            return false;
        }
        if (env('QINIU_STATUE') === true) {
            // 不是uuid格式，那么就直接返回地址
            if (!preg_match('/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/', $key)) {
                $imageUrl = \App\Libraries\Qiniu\FileManagement::DOMAIN . $key;
                return $imageUrl . sprintf("?imageView2/1/w/%d/h/%d", $param['w'] ?? 0, $param['h'] ?? 0);
            } else {
                return \App\Libraries\Qiniu\FileManagement::DOMAIN . (new \App\Models\DisFile())->fileId2url($key) . sprintf("?imageView2/1/w/%d/h/%d", $param['w'] ?? 0, $param['h'] ?? 0);
            }
        }
    }
}

/**
 * @param $array_str  使用「点」式语法从深度嵌套数组中取回指定的值 ，如：products.desk 就是取出数组products下的desk值
 * @return mixed
 */
function get_admin_session_info($array_str=''){
    $admin_session_prefix = config('admin_config.SESSION_ADMIN_PREFIX');
    $sessionInfo = session($admin_session_prefix);
    if($array_str){
        $result = array_get($sessionInfo,$array_str);
    }else{
        $result = $sessionInfo;
    }
    return $result;
}
function array2object($array) {
    if (is_array($array)) {
        $obj = new StdClass();
        foreach ($array as $key => $val){
            $obj->$key = $val;
        }
    }
    else { $obj = $array; }
    return $obj;
}
function object2array($object) {
    if (is_object($object)) {
        foreach ($object as $key => $value) {
            $array[$key] = $value;
        }
    }
    else {
        $array = $object;
    }
    return $array;
}
function get_adminlist_status_name($status = 2){
    $status_name = '';
    switch($status){
        case 1:
            $status_name = '未锁定';
            break;
        case 2:
            $status_name = '已锁定';
            break;
    }
    return $status_name;
}

//反向处理数组(根据id 获取其所有父级)
function return_old_recur_n($list,$new_array = []){

    if(is_array($list)){
        foreach($list as $k=>$v){
            if(empty($v['child'])){
                $new_array[] = $v;
            }else{

                return_old_recur_n($v,$new_array);
            }


        }
        return array_values($list);
    }
}

/**
+----------------------------------------------------------
 * 将一个字符串部分字符用*替代隐藏
+----------------------------------------------------------
 * @param string    $string   待转换的字符串
 * @param int       $bengin   起始位置，从0开始计数，当$type=4时，表示左侧保留长度
 * @param int       $len      需要转换成*的字符个数，当$type=4时，表示右侧保留长度
 * @param int       $type     转换类型：0，从左向右隐藏；1，从右向左隐藏；2，从指定字符位置分割前由右向左隐藏；3，从指定字符位置分割后由左向右隐藏；4，保留首末指定字符串
 * @param string    $glue     分割符
 * @param string    $end      替换字符
+----------------------------------------------------------
 * @return string   处理后的字符串
+----------------------------------------------------------
 */
function hide_str($string, $bengin=0, $len = 4, $type = 0, $glue = "@",$end='*') {
    if (empty($string))
        return false;
    $array = array();
    if ($type == 0 || $type == 1 || $type == 4) {
        $strlen = $length = mb_strlen($string);
        while ($strlen) {
            $array[] = mb_substr($string, 0, 1, "utf8");
            $string = mb_substr($string, 1, $strlen, "utf8");
            $strlen = mb_strlen($string);
        }
    }
    if ($type == 0) {
        for ($i = $bengin; $i < ($bengin + $len); $i++) {
            if (isset($array[$i]))
                $array[$i] = $end;
        }
        $string = implode("", $array);
    }else if ($type == 1) {
        $array = array_reverse($array);
        for ($i = $bengin; $i < ($bengin + $len); $i++) {
            if (isset($array[$i]))
                $array[$i] = $end;
        }
        $string = implode("", array_reverse($array));
    }else if ($type == 2) {
        $array = explode($glue, $string);
        $array[0] = hide_str($array[0], $bengin, $len, 1);
        $string = implode($glue, $array);
    } else if ($type == 3) {
        $array = explode($glue, $string);
        $array[1] = hide_str($array[1], $bengin, $len, 0);
        $string = implode($glue, $array);
    } else if ($type == 4) {
        $left = $bengin;
        $right = $len;
        $tem = array();
        for ($i = 0; $i < ($length - $right); $i++) {
            if (isset($array[$i]))
                $tem[] = $i >= $left ? $end : $array[$i];
        }
        $array = array_chunk(array_reverse($array), $right);
        $array = array_reverse($array[0]);
        for ($i = 0; $i < $right; $i++) {
            $tem[] = $array[$i];
        }
        $string = implode("", $tem);
    }
    return $string;
}

/**
 * @param string $msg
 * @param string $callback_url
 * @param int $countdown        毫秒，默认3000 即3秒
 */
function tipspageshow($msg='请求出错', $callback_url="window.history.back()", $countdown= 3000 ){
    /**@wym**/
    echo  view('errors.sorry',['msgData'=>['msg'=>$msg,'url'=>$callback_url, 'time'=>$countdown]]);die;
}

//字符串截取（暂时没用，替换为使用laravel自带辅助函数str_limit()）
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true)//截取中文字符
{
    if (strlen($str) / 3 > $length) {
        if (function_exists("mb_substr")) {
            $str = mb_substr($str, $start, $length, $charset);
            return $suffix ? $str . '…' : $str;
        } elseif (function_exists('iconv_substr')) {
            $str = iconv_substr($str, $start, $length, $charset);
            return $suffix ? $str . '…' : $str;
        }
        $re['utf-8'] = "[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));

        return $suffix ? $slice . '...' : $slice;
    }
    return $str;
}

/**
 * @param int $length
 * @return string
 */
function create_hash($length = 8)
{
    // 密码字符集，可任意添加你需要的字符
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        // 这里提供两种字符获取方式
        // 第一种是使用 substr 截取$chars中的任意一位字符；
        // 第二种是取字符数组 $chars 的任意元素
        // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        $password .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $password;
}

//获取加密固定码
function get_fixed_code(){
    $str = 'DXEJxoBTLfTcy19j';
    return $str;
}

//第二版所用加密方式
function dx_hash_bcrypt($password,$type = false){
    $hash_str = create_hash();  //生成的盐值8位
    $fixed_code = get_fixed_code();//默认固定密码
    $password = password_hash($password.$fixed_code.$hash_str, PASSWORD_DEFAULT);
    if($type){
        $return['hash_str'] = $hash_str;
        $return['password'] = $password;
        return $return;
    }
    return $password;
}

//第二版所用密码验证方法
function dx_hash_bcrypt_check($checked_password,$hash_str,$password){
    $fixed_code = get_fixed_code();//默认固定码
    if (password_verify($checked_password .$fixed_code. $hash_str, $password)) {
        return true;
    }
    return false;
}


//格式化日期
function get_newdate($Ymd,$format='Y-m-d'){
    $res = date($format,strtotime($Ymd));
    return $res;
}


/******************图形验证码相关方法***********************/
if ( ! function_exists('captcha')) {

    /**
     * @param string $config
     * @return mixed
     */
    function captcha($config = 'default')
    {
        return app('captcha')->create($config);
    }
}

if ( ! function_exists('captcha_src')) {
    /**
     * @param string $config
     * @return string
     */
    function captcha_src($config = 'default')
    {
        return app('captcha')->src($config);
    }
}

if ( ! function_exists('captcha_img')) {

    /**
     * @param string $config
     * @return mixed
     */
    function captcha_img($config = 'default')
    {
        return app('captcha')->img($config);
    }
}


if ( ! function_exists('captcha_check')) {
    /**
     * @param $value
     * @return bool
     */
    function captcha_check($value)
    {
        return app('captcha')->check($value);
    }
}
//图形验证码相关方法结束



/*
 * 检查整数值是否在范围之类
 */
function check_str_length($length,$begin = 6,$end = 16){
    if($length < $begin || $length > $end){
        return true;
    }
    return false;
}



if (!function_exists("detection_password")) {
    /**
     * 检查密码强度，  满分10分； 6==及格；
     * 8-16位
     * @param string $passwordStr
     * @return bool|int
     */
    function detection_password($passwordStr = '') {
        if (empty($passwordStr)) {
            return false;
        }
        if (strlen( $passwordStr ) < 8 )
        {
            return 1;
        }
        $strength = 0;
        $length = strlen($passwordStr);
        if(strtolower($passwordStr) != $passwordStr)
        {
            $strength += 1;
        }
        if(strtoupper($passwordStr) != $passwordStr)
        {
            $strength += 1;
        }
        if($length >= 8 && $length <= 12)
        {
            $strength += 1;
        }
        if ($length > 12 && $length < 16) {
            $strength += 2;
        }
        if($length >= 16)
        {
            $strength += 3;
        }
        if (!preg_match('/\d{8,16}/',$passwordStr)) {
            $strength += 1;
        }
        if (!preg_match('/[a-z]{8,16}/', $passwordStr)) {
            $strength += 1;
        }
        if (!preg_match('/[A-Z]{8,16}/', $passwordStr)) {
            $strength += 1;
        }
        if(preg_match('/[|!@#$%&*\/=?,;.:\-_+]/', $passwordStr))
        {
            $strength += 2;
        }
        return $strength;
    }
}


if(!function_exists('get_admin_menu')){
    function get_admin_menu () {
        $requestUrlData = session('requestUrl');
        // 缓存读取导航栏
//        $admin_nav_list = get_admin_session_info('moduleList');
        // 实时数据库查询获取导航栏
        $user_id = get_admin_session_info('id');
        $admin_nav_list = \App\Models\Jurisdiction::getAuthList($user_id);


        krsort($requestUrlData);
        $requestUrlData = array_unique($requestUrlData);
        $tab = false;
        if ($requestUrlData) {
            foreach ($requestUrlData as $value) {
                foreach ($admin_nav_list['node_list'] as $key=>$val) {
                    if (isset($val['child'])) {
                        foreach ($val['child'] as $q=>$w) {
                            if (isset($w['child'])) {
                                foreach ($w['child'] as $e=>$r) {
                                    if ($r['code']) {
                                        if (ltrim($r['code'],'/') == ltrim($value,'/')) {
                                            $admin_nav_list['node_list'][$key]['class'] = ' activeLi';
                                            $admin_nav_list['node_list'][$key]['menu_class'] = 'nav-active';
                                            $admin_nav_list['node_list'][$key]['child'][$q]['class'] = 'nav-active active';
                                            $admin_nav_list['node_list'][$key]['child'][$q]['child'][$e]['class'] = 'active';
                                            $tab = true;
                                            break 4;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if ($admin_nav_list) {
            $keysData = array_keys($admin_nav_list['node_list']);
            if (!empty($keysData)) {
                if ($tab === false) {
                    $admin_nav_list['node_list'][$keysData[0]]['class'] = ' activeLi';
                    $admin_nav_list['node_list'][$keysData[0]]['menu_class'] = ' nav-active';
                }
            }
        }
        return $admin_nav_list ?? [];
    }
}

/**
 * 返回失败
 *
 * @param string $msg
 * @param int $status
 * @param array $data
 * @return \Illuminate\Http\JsonResponse
 * @author dch
 */
function response_error($msg = '', $status = -1, $data = [])
{
    return response()->json(['status' => $status, 'msg' => $msg, 'data' => $data]);
}

/**
 * 返回成功
 *
 * @param array $data
 * @param string $msg
 * @return \Illuminate\Http\JsonResponse
 * @author dch
 */
function response_success($data = [],$msg = '')
{
    return response()->json(['data'=>$data,'status'=>1,'msg'=>$msg]);
}

function response_message($msg, $status = 1, $data = [])
{
    return response()->json(['status' => $status, 'msg' => $msg, 'data' => $data]);
}


/**
 * 纯正数检测（负数和小号都不会通过）
 * @param $input
 * @return bool
 * @User: long-yun-rui
 */
function isInteger($input){
    return(ctype_digit(strval($input)));
}

/**
 * 路由不存在不抛出异常
 *
 * @param $name
 * @param array $parameters
 * @param bool $absolute
 * @return string
 * @author dch
 */
function to_route($name, $parameters = [], $absolute = true){
    try{
        $urlPath = route($name, $parameters, $absolute);
        $parseUrl = parse_url($urlPath);
        if (false === strpos($parseUrl['path'], '.htm')) {
            return str_replace($parseUrl['path'], $parseUrl['path'].'/', $urlPath);
        }
        return  $urlPath;
    }catch(\Throwable $e){
        \Illuminate\Support\Facades\Log::error($e);
    }
    return '';
}

if (!function_exists('admin_log')){
    /**
     *
     * @author: liufangyuan
     * @date: 2016-12-8
     * @param $mark
     * @param $data
     */
    function admin_log($mark,$data){
        $insertArray = [
            'created_at' => date('Y-m-d H:i:s'),
            'json_string' => json_encode($data),
            'mark' => $mark,

        ];
        if (!empty($data['id'])){
            $insertArray['aims_id'] =$data['id'];
        }
        \Illuminate\Support\Facades\DB::table('admin_log') -> insert($insertArray);
    }
}


/**
 * json格式转为行格式
 *
 * @param $json
 * @return string
 * @author dch
 */
function json2row($json)
{
    $arr = json_decode($json,true);
    if(empty($arr)){
        return '';
    }
    $ret = '';
    foreach ($arr as $key => $value) {
        $ret .= sprintf("%s:%s%s",$key,$value,PHP_EOL);
    }
    return $ret;
}


function get_user_session_info($array_str = ''){
    try {
        if (!session('member')){
            return null;
            /*$sessionInfo = \Illuminate\Support\Facades\DB::table('user_association as u')
                ->join('home_user as h','h.user_uuid','=','u.uuid')
                ->where('sso_id',$brokerUserSessionInfo['id'])->first();
            $sessionInfo = object2array($sessionInfo);
            session(['member'=>$sessionInfo]);*/
        }else {
            $sessionInfo = session('member');
        }
        if ($array_str) {
            $result = array_get($sessionInfo, $array_str);
        } else {
            $result = $sessionInfo;
        }
        /*开发阶段临时数据--结束*/
        return $result;
    } catch (Throwable $exception) {
        Log::warning($exception);
    }
    return null;
}


if ( !function_exists("set_user_session_info") ) {
    function set_user_session_info ($field = '',$value = null) {
        if (empty($field)) {
            return false;
        }
        $sessionInfo = session('member');
        if (empty($sessionInfo) || !in_array($field, array_keys($sessionInfo))) {
            Log::warning('未找到用户的信息');
            return false;
        }
        if (is_null($value)) {
            unset($sessionInfo[$field]);
        } else {
            $sessionInfo[$field] = $value;
        }
        session(['member'=>$sessionInfo]);
        Session::save();
        return true;
    }
}

