<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SysNode
 *
 */
class SysNode extends Model
{
    //
    protected $table = 'sys_node';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
    public static $snakeAttributes = false;   //设置关联模型在打印输出的时候是否自动转为蛇型命名
    protected $casts = [
        'parent_id' => 'int',
        'is_show'   => 'boolean',
        'ico'       => 'string',
        'title'     => 'string',
        'sort'      => 'int',
        'route'     => 'string',
        'tree'      => 'string'
    ];
}
