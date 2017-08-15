<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DxUserOperationLog
 *
 * @mixin \Eloquent
 */
class UserOperationLog extends Model
{
    protected $table = 'user_operation_log';
    protected $dateFormat = 'U';
    public $timestamps = false;         //是否有created_at和updated_at字段
    protected $guarded = ['id'];

    /*
     * @param $userid   操作人id
     * @param $from_type    来源类型 1 后台员工操作 2 前台用户操作
     * @param $operation_type   操作类型 1 会员登录；2 注册；3 后台员工登录；4 会员申请资质认证；
     * @param $time 操作时间
     * @param $remark   操作备注
     * @param $ip   操作ip
     * @param $city 所在城市
     * @param $from_url 请求来源地址
     * @param $now_url  请求目的地址
     * @param string $operation_id  操作的id（后台员工审核的时候用到，审核的是哪条信息，这里就是那条信息的id）
     * @param string $operation_userid  操作的id对应的userid（后台员工审核的时候用到，审核的是哪条信息，这里就是那条信息的用户id）
     * @param string $admin_operation_type  后台审核操作类型,只针对认证审核相关操作;1 后台通过资质认证；2 后台拒绝资质认证（后台操作必填！）
     * @return bool
     */
    public static function addLog($userid, $from_type = 1, $operation_type = 2, $time = null, $remark, $ip = null, $city, $from_url, $now_url, $operation_id = 0, $operation_userid = 0,$admin_operation_type = 0){
        $data['user_id'] = $userid;
        $data['from_type'] = $from_type;
        $data['operation_type'] = $operation_type;
        $data['admin_operation_type'] = $admin_operation_type;
        $data['operation_id'] = $operation_id;
        $data['operation_userid'] = $operation_userid;
        $data['operation_time'] = $time ?? time();
        $data['remark'] = $remark;
        $data['operation_ip'] = $ip ?? get_client_ip();
        $data['operation_city'] = $city;
        $data['from_url'] = $from_url;
        $data['now_url'] = $now_url;
        $result = parent::create($data);
        if($result){
            return true;
        }
        return false;
    }

    //获取操作记录
    public static function getLogList($where = [],$field = '*',$lastPage = 10){
        $list = parent::where($where)->select($field)->orderBy('operation_time','desc')->take($lastPage)->get();
        return $list;
    }
}
