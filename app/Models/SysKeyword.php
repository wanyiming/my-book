<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 关键词配置
 * @author:wym
 * Class Recommend
 * @package App\Model
 */
class SysKeyword extends Model
{

    public static $keyword = '';

    public $timestamps = false;

    protected $table = 'sys_keyword';

    const PAGE_NUM = 15;

    const CATCH_TIME = 60; //分钟

    const STATUS_NORMAL = 1;//正常
    const STATUS_DISABLE = 2;//禁用
    const STATUS_DELETE = 99;//删除
    const STATUS_ARR = [
        self::STATUS_NORMAL => ['name' => '正常', 'class' => LABEL_SUCCESS],
        self::STATUS_DISABLE => ['name' => '禁用', 'class' => LABEL_DEFAULT],
        self::STATUS_DELETE => ['name' => '删除', 'class' => LABEL_DELETE],
    ];

    protected $fillable = [
        'id', 'name', 'url', 'status', 'create_at','weight'
    ];

    /**
     * 暂未启用站点关键词设置
     * 读取缓存数据信息
     * @return mixed
     */
    public static function getSysKeyword($site = 0)
    {
        $cookieKeyword = $_COOKIE['search_keyword'] ?? '';
        if (empty($cookieKeyword)) {
            $cookieKeyword =  \Cache::remember('catchKeyword', self::CATCH_TIME, function () {
                return \DB::table('sys_keyword')->where('status',self::STATUS_NORMAL)->take(6)->orderBy('weight','desc')->orderBy('id','desc')->pluck('url','name')->toArray();
            });
        } else {
            parse_str($cookieKeyword,$cookieKeyword);
            foreach ($cookieKeyword as $key => $url){
                if(empty($url) || empty($key)){
                    unset($cookieKeyword[$key]);
                }
                if(!(is_string($key) && is_string($url))){
                    unset($cookieKeyword[$key]);
                }
            }
        }
        $newCookieData = [];
        if (is_array($cookieKeyword)) {
            foreach ($cookieKeyword as $k=>$v) {
                $newCookieData[] = ['name'=>$k,'url'=>$v];
            }
        }

        //选中当前的url
        foreach ($newCookieData as $key=>$value) {
            $class  = $_SERVER['REQUEST_URI'] == $value['url'] ? 'active' : '';
            $newCookieData[$key]['class'] = $class;
        }
        return $newCookieData;
    }
}