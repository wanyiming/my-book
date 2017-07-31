<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendlyLink extends Model
{
    //
    protected $table = 'friendly_link';
    public $timestamps = false;
    protected $fillable = ['id','website','weburl','email','content','sort'];

    const LINK_STATUS_SUCCESS = 1; // 友情连接信息状态   审核通过
    const LINK_STATUS_FAIL = 2; // 友情连接信息状态  审核未通过
    const LINK_STATUS_PENDING = 3; // 友情连接信息状态  待审核
    const LINK_STATUS_DEL = 99; // 友情连接信息状态   已删除


    public static function friendlyData ($objectType = Recommend::FRIENDLY_LIST) {
        $objectId = (new Recommend())->getObjectId(Recommend::OBJECT_TYPE_FRIENDLY, $objectType, 100);
        if (empty($objectId)) {
            return [];
        }
        $objectData =  self::where('status', self::LINK_STATUS_SUCCESS)->whereIn('id', array_unique($objectId))->select('weburl', 'website')->orderBy('sort','asc')->get();
        if ($objectData->isEmpty() === true) {
            return [];
        }
        return  $objectData;
    }

}
