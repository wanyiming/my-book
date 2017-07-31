<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 推荐
 * Class Recommend
 * @package App\Model
 */
class Recommend extends Model
{
    public $timestamps = false;

    protected $table = 'recommend';

    //1商品,2厂家,3经销商,4需求;5友情链接
    const OBJECT_TYPE_BOOK         = 1;
    const OBJECT_TYPE_FRIENDLY        = 5;

    const OBJECT_POSITION_HOME = 1; //首页
    const OBJECT_POSITION_LIST = 2; //列表
    const OBJECT_POSITION_DETAILS = 4; //详情
    const OBJECT_POSITION_ERROE = 8; //出错页面推荐


    const FRIENDLY_HOME = self::OBJECT_POSITION_HOME; // 首页
    const FRIENDLY_XIEYI = self::OBJECT_POSITION_DETAILS; // 协议写
    const FRIENDLY_LIST = self::OBJECT_POSITION_LIST; // 列表

    const OBJECT_TYPE_ARRAY = [self::OBJECT_TYPE_BOOK=>'书本', self::OBJECT_TYPE_FRIENDLY => '友情链接'];
    const OBJECT_POSITION_ARRAY = [self::OBJECT_POSITION_HOME=>'首页', self::OBJECT_POSITION_LIST=>'列表',self::OBJECT_POSITION_DETAILS=>'详情', self::OBJECT_POSITION_ERROE => 'error页面'];

    const FRIENDLY_ATT = [
        self::FRIENDLY_HOME => '首页友情链接',
        self::FRIENDLY_XIEYI => '协议页友情链接',
        self::FRIENDLY_LIST => '其它页友情链接'
    ];

    protected $fillable = [
        'id','object_id','weight','object_position','create_at','begin_at','end_at','object_type','object_remark','admin_id'
    ];

    /**
     * 返回推荐的信息id
     * @param int $type 类型
     * @param int $position 位置
     * @param int $limit 条数
     * @return array
     */
    public function getObjectId (int $type, int $position, int $limit = 5) {
        if (empty($type) || empty($position)) {
            return [];
        }
        return $this->where('object_type', $type)
            ->where('object_position','&',$position)
            ->where('begin_at','<',date('Y-m-d H:i:s',time()))
            ->where('end_at','>',date('Y-m-d H:i:s',time()))
            ->orderBy('weight', 'asc')->limit($limit)->pluck('object_id')->toArray();
    }

    /**
     * 检查是否推荐
     * @param int $objectType 推荐的类型
     * @param int $objectId 推荐的信息id
     * @return bool false 未推荐； true 推荐
     */
    public function checkRecommend (int $objectType, int $objectId) {
        if (empty($objectType) || empty($objectId)) {
            return false;
        }
        return (bool)$this->where('object_id',$objectId)->where('object_type' , $objectType)->exists();
    }

    /**
     * 返回此需求在什么地方做了推荐
     * @param int $objectType
     * @param int $objectId
     * @return array
     */
    public function getRecommendData (int $objectType, int $objectId ) {
        if (empty($objectId) || empty($objectId)) {
            return false;
        }
        $recommendList = $this->where('object_id',$objectId)->where('object_type' , $objectType)->get();
        foreach ($recommendList as $k=>$v) {
            $v->obj_type_name = self::OBJECT_TYPE_ARRAY[$v->object_type];
        }
        return $recommendList;
    }

    /**
     * 保存配置
     * @param $newData
     */
    public static function edit($newData) {
        if (empty($newData)) {
            return response_error('请求参数异常');
        }
        $redcommendDB = \DB::table('recommend');
        \DB::beginTransaction();
        $redcommendDB->where('object_id', $newData['objid'])->where('object_type', $newData['objtype'])->delete();
        $checkInt = true;
        if (!empty($newData['weight'])) {
            $array = [];
            foreach ($newData['weight'] as $k=>$v) {
                $array[] = [
                    'object_id' => intval($newData['objid']),
                    'weight' => intval($newData['weight'][$k]),
                    'object_position' => $newData['object_position'][$k],
                    'begin_at' => $newData['begin_at'][$k],
                    'end_at' => $newData['end_at'][$k],
                    'object_type' => intval($newData['objtype']),
                    'object_remark' =>  $newData['object_remark'][$k],
                ];
            }
            $checkInt = self::insert($array);
        }
        if ($checkInt) {
            \DB::commit();
            return response_success('配置成功');
        }
        \DB::rollBack();
        return response_error('配置失败');
    }

    /**
     * 返回所有推荐的信息ObjectID
     * @param $objectType
     * @return array
     */
    public function getRecommendTypeData ($objectType) {
        if (empty($objectType)) {
            return [];
        }
        return $this->where('object_type', $objectType)->pluck('object_id')->toArray();;
    }

}