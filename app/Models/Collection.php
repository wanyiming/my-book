<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 收藏模型
 * @author  liufangyuan
 * Class Collection
 * @package App\Models
 */
class Collection extends Model
{
    protected $table = 'collection';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
    public static $snakeAttributes = false;   //设置关联模型在打印输出的时候是否自动转为蛇型命名

    const GOODS_TYPE = 1;//商品收藏
    const SHOP_TYPE = 2;//店铺收藏

    const HAS_COLLECTION_STATE = 3; // 重复收藏
    const COLLECTION_FAIL = -2; // 收藏失败


    /**
     * 返回被关注的信息
     * @param array $aimsIdArr
     * @param int $type
     * @return array
     */
    public function getCollectionData (array $aimsIdArr, $type = self::GOODS_TYPE) {
        if (empty($aimsIdArr)) {
            return [];
        }
        return self::whereIn('aims_id', $aimsIdArr)
            ->where('type',$type)->where('user_uuid', get_user_session_info('user_uuid',1))->pluck('aims_id','aims_id')->toArray();
    }


    /**
     * 添加收藏信息
     * @author: liufangyuan
     * @date: 2016-12-8
     * @param int $aimsId 目标ID
     * @param int $type 收藏类型
     * @return bool
     */
    public function addCollection(int $aimsId,$type = null)
    {
        $insertArray = [
            'created_at' => date('Y-m-d H:i:s'),
            'aims_id' => $aimsId,
            'user_uuid' => get_user_session_info('user_uuid'),
            'type' => $type ?? self::GOODS_TYPE,
            'status' =>1,
        ];
        //验证数据
        $checkInfo = self::where('user_uuid',get_user_session_info('user_uuid'))->where('type',$type)->where('aims_id',$aimsId)->first();
        if($checkInfo) {
            return self::HAS_COLLECTION_STATE;//已经收藏
        }
        $result = self::insert($insertArray);
        if ($result){
            return true;
        }else {
            return false;
        }
    }
}
