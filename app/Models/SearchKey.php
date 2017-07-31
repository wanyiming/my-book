<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Ramsey\Uuid\Uuid;

/**
 * 搜索
 * @author:wym
 * Class Recommend
 * @package App\Model
 */
class SearchKey extends Model
{

    public static $keyword = '';
    public static $objectType = 1;
    public static $cookieMinutesTime = 0;

    const OBJECT_SERVICE = 3;//服务
    const OBJECT_DEMAND = 2;//需求
    const OBJECT_SHOP = 1;//服务商
    const OBJECT_ARR = [
        self::OBJECT_SERVICE => ['name'=> '服务信息','class' => LABEL_SUCCESS],
        self::OBJECT_DEMAND => ['name' => '需求信息' ,'class' => LABEL_DEFAULT],
        self::OBJECT_SHOP => ['name' => '服务商' ,'class' => LABEL_PRIMARY],
    ];

    public $timestamps = false;

    protected $table = 'search_key';

    protected $fillable = [
        'id','keyword','request_link','create_time','ip','pid','user_id','session'
    ];

    /**
     * 添加搜索关键词
     * @param string $key
     * @return bool
     */
    public static function insertSearch()
    {
        //设置一天后过期
        self::$cookieMinutesTime = time()+86400;
        if (!session('search_uuid')) {
            session(['search_uuid'=>Uuid::uuid4()]);
        }

        $newData = [
            'keyword' => mb_substr(self::$keyword ?? '', 0, 10),
            'request_link' => $_SERVER['REQUEST_URI'] ?? '',
            'ip' => request()->ip() ?? '',
            'user_id' => get_user_session_info('id') ?? 0,
            'session' =>session('search_uuid',''),
            'object_type' => self::$objectType,
            'scoure_site' => get_current_site()['area_name'] ?? ''
        ];
        $resultArr = getTokenizerSCWS(self::$keyword);
        $newDataArr = [];
        if ($resultArr == -1) { // 未开启搜索引擎服务
            $newData['scws_str'] = self::$keyword ?? '';
        } else {
            if (count($resultArr) <= 1) {
                $newData['scws_str'] = htmlspecialchars(trim($resultArr[0] ?? ''));
            } else {
                $newDataArr = [];
                foreach ($resultArr as $scwsKey => $scwsValue) {
                    $newData['scws_str'] = htmlspecialchars(trim($scwsValue));
                    $newDataArr [] = $newData;
                }
            }
        }
        if (empty($newDataArr)) {
            $newDataArr = $newData;
        }
        SearchKey::insert($newDataArr);
        self::setCookieSearchKey(self::$keyword,request()->server('REQUEST_URI'));
    }

    public static function setCookieSearchKey($keyword='',$link='/'){
        if(mb_strlen($keyword) > 5){
            return ;
        }
        $searchKeyword = $_COOKIE['search_keyword'] ?? '';
        parse_str($searchKeyword,$params);
        $params = (array)$params;

        foreach ($params as $key => $url){
            if(empty($url) || empty($key)){
                unset($params[$key]);
            }
            if(!(is_string($key) && is_string($url))){
                unset($params[$key]);
            }
        }
        $params = array_slice($params,0,5);
        setcookie('search_keyword',http_build_query(array_merge([strval($keyword)=>strval($link)],$params)), self::$cookieMinutesTime);
        return ;
    }

}