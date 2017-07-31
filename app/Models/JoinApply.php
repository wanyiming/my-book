<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JoinApply extends Model
{
    protected $table = 'join_apply';
    //public $timestamps = false;
    //protected $dateFormat = 'U';
    protected $guarded = ['id'];
    public static $snakeAttributes = false;   // 设置关联模型在打印输出的时候是否自动转为蛇型命名

    const JOIN_APPLY_TYPE_MEMBER = 1;           // 首页广告申请
    const JOIN_APPLY_TYPE_MANUFACTURER = 2;     // 列表广告身亲
    const JOIN_APPLY_TYPE_DEALER = 3;           // 详情广告申请
    const JOIN_APPLY_TYPE_CONTACT_US = 4;           // 书本详情申请

    const TITLE_LENGTH_MIN = 1;         // 标题最小字符长度
    const TITLE_LENGTH_MAX = 50;        // 标题最大字符长度
    const NAME_LENGTH_MIN = 1;        // 联系人最小字符长度
    const NAME_LENGTH_MAX = 20;        // 联系人最大字符长度

    const STATUS_READE_NOT = 1; // 阅读状态 1 未读 2 已读 99 删除
    const STATUS_READE_YES = 2; // 阅读状态 1 未读 2 已读 99 删除
    const STATUS_READE_DEL = 99; // 阅读状态 1 未读 2 已读 99 删除

    public static function getStatusGroup($status = ''){
        $group = [
            self::STATUS_READE_NOT => '未读',
            self::STATUS_READE_YES => '已读',
        ];
        return $group[$status] ?? $group;
    }

    public static function getTypeGroup($status = ''){
        $group = [
            self::JOIN_APPLY_TYPE_MEMBER => '首页广告',
            self::JOIN_APPLY_TYPE_MANUFACTURER => '列表广告',
            self::JOIN_APPLY_TYPE_DEALER => '详情广告',
            self::JOIN_APPLY_TYPE_CONTACT_US => '书本详情广告',
        ];
        return $group[$status] ?? $group;
    }
}
