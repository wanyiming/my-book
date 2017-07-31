<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    public $timestamps = false;

    protected $table = 'ad';
    protected $fillable = [
        'id','picture_url','ad_name','ad_link','begin_time','end_time','remark','admin_id','create_at','position_id','weight'
    ];
}