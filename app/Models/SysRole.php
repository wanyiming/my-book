<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SysRole
 * @package App\Models
 * @author dch
 */
class SysRole extends Model
{
    protected $table = 'sys_role';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = [];
    protected $casts = [
        'authority' => 'array'
    ];

}
