<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookType extends Model
{
    //
    protected $table = 'book_type';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
    public static $snakeAttributes = false;   //设置关联模型在打印输出的时候是否自动转为蛇型命名


    public function getTypeName ($uuid) {
        if (empty($uuid)) {
            return '未找到改分类';
        }
        return self::where('key', $uuid)->value('name') ?? '未找到该分类';
    }
}
