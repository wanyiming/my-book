<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SysPosition
 *
 * @mixin \Eloquent
 */
class SysPosition extends Model
{
    //
    protected $table = 'sys_position';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
    public static $snakeAttributes = false;   //设置关联模型在打印输出的时候是否自动转为蛇型命名

    //获取所有职位名称
    public static function getSysPositionInfo($where = []){
        if(empty($where)){
            $where['status'] = 1;
        }
        $list = parent::where($where)->pluck('name','id')->toArray();
        return $list;
    }
}
