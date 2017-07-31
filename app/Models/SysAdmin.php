<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 后台管理员模型
 *
 * App\Models\SysAdmin
 */
class SysAdmin extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sys_admin';

    protected     $dateFormat      = 'U';
    public        $timestamps      = false;   //是否有created_at和updated_at字段
    public static $snakeAttributes = false;   //设置关联模型在打印输出的时候是否自动转为蛇型命名

    const MIN_PASSWORD_LEVEL = 6;//1-10 10最高级

    const SUPER_ADMIN_ID = 5;   //超级管理员id

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'role_ids' => 'array'
    ];


    public static function getRoleName ($sysId) {
        if (empty($sysId)) {
            return '';
        }
        $roleId = self::where('id', $sysId)->value('role_ids');
        if (empty($roleId)) {
            return '';
        }
        if ($roleId == 9) {
            return '超级管理员';
        }
        $roleNameArr = SysRole::whereIn('id', (is_array($roleId) ? $roleId : [$roleId]))->pluck('name')->toArray();
        if (empty($roleNameArr)) {
            return '';
        }
        return implode('-', $roleNameArr);
    }
}
