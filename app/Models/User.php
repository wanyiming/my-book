<?php

namespace App\Models;

use Faker\Provider\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Mockery\CountValidator\Exception;

/**
 * 用户体系模型
 *
 * @author liufangyuan
 * Date: ${DATE}
 * Time: ${TIME}
 * Class User
 * @package App\Models
 * @mixin \Eloquent
 */
class User extends Model
{
    const STATUS_OFF = 2;  // 暂停
    const STATUS_ON = 1; // 启用

    public $table = 'user';

    const STATUS_ALL = [
        self::STATUS_ON => ['name'=>'正常','class'=>LABEL_SUCCESS],
        self::STATUS_OFF => ['name'=>'停用','class'=>LABEL_DEFAULT],
    ];

    const SEX_MAC = '男';
    const SEX_GIRL = '女';
    const SEX_ALL = [
        self::SEX_MAC => ['name'=>'男','class'=>LABEL_SUCCESS],
        self::SEX_GIRL => ['name'=>'女','class'=>LABEL_DEFAULT],
    ];
    public $timestamps = false;


}
