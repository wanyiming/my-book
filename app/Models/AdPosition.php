<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use stdClass;

/**
 * 广告位Model
 *
 * Class AdPosition
 * @package App\Model
 * @author dch
 */
class AdPosition extends Model
{
    const STATUS_ENABLE  = 1;//启用
    const STATUS_DISABLE = 2;//停用
    const STATUS_DELETE = 99;//已删除
    const STATUS_ARR = [
        self::STATUS_ENABLE => ['name'=> '启用','class' => LABEL_SUCCESS],
        self::STATUS_DISABLE => ['name' => '禁用' ,'class' => LABEL_DEFAULT],
        self::STATUS_DELETE => ['name' => '删除' ,'class' => LABEL_DELETE],
    ];

    public $timestamps = false;

    protected $table    = 'ad_position';
    protected $fillable = [
        'id', 'call_key', 'sub_channel', 'position_name', 'display_mode', 'width', 'height', 'option', 'status'
    ];

    public function getAdAndPosition(string $callKey)
    {
        $position = $this->where([['call_key', '=', strtoupper($callKey)]])->first();
        if (!$position) {
            return $position;
        }

        $now = date('Y-m-d H:i:s');
        $adAndPosition = new stdClass();
        $adAndPosition->position = $position;
        $adAndPosition->ads = Ad::where([
            ['position_id','=' ,$position['id']],
            ['begin_time','<',$now],
            ['end_time','>',$now],
            ['status','=',self::STATUS_ENABLE]
        ])->orderBy('weight','asc')->get();

        return $adAndPosition;
    }

    //显示模式
    public function getDisplayMode()
    {
        return [
            1 => '单图',
            2 => '多图'
        ];
    }

    //前期固定即可
    public function getSubChannel()
    {
        return [
            1010 => '主页',
            1020 => '建筑财务',
            1030 => '建筑法务',
            1040 => '建筑培训',
        ];
    }
}