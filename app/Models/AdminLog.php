<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    public $timestamps = false;

    protected $table = 'admin_log';


    public static function getList (string $tableModel, int $targetId) {
        if (empty($tableModel) || empty($targetId)) {
            return [];
        }
        return self::where('aims_id', $targetId)->where('mark', $tableModel)->get();
    }
}