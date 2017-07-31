<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PubSeoRule extends Model
{
    public $timestamps = false;
    protected $table = 'pub_seo_rule';
    protected $fillable = [
        'id', 'call_key', 'page_name','title','keywords','description'
    ];
}
