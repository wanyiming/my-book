<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Comment extends Model
{
    protected $perPage = 3;
    const STATUS_NO = 1;//审核中
    const STATUS_NORMAL = 2;//正常
    const STATUS_STY = [
        self::STATUS_NORMAL => ['name'=>'正常','class'=>LABEL_SUCCESS],
        self::STATUS_NO => ['name'=>'停用','class'=>LABEL_DEFAULT],
    ];

    const PAGE_ROWS = 15;

    public $timestamps = false;

    protected $table    = 'comment';
    protected $fillable = [
        'id', 'name', 'content', 'create_time', 'status', 'user_uuid','book_uuid',
    ];

    /**
     * 列表
     * @param array $param
     * @return mixed
     */
    public function commentLists (array $param) {
        $lists = Comment::where(function($query) use($param){
            if ($param['status']) {
                $query->where('status', intval($param['status']));
            }
        })->where(function($query) use ($param){
            if($param['begin_time'] && !$param['end_time']){
                $query->where('create_time','>=' ,date('Y-m-d',strtotime($param['begin_time'])));
            } else if (!$param['begin_time'] && $param['end_time']) {
                $query->where('create_time','<=' ,date('Y-m-d',strtotime($param['end_time'])));
            } else if ($param['begin_time'] && $param['end_time']) {
                $query->whereBetween('create_time',[date('Y-m-d',strtotime($param['begin_time'])),date('Y-m-d',strtotime($param['end_time']))]);
            }
        })->orderBy('id', 'desc')->paginate(Comment::PAGE_ROWS);
        $sensitiveArray = [];
        if (!empty($lists)) {
            foreach ($lists as $key=>$value) {
                $sensitiveArray[] = [
                    'id' =>  $value['id'],
                    'text' => $value['content'],
                ];
            }
        }
        // 评价书本
        $bookData = Books::whereIn('uuid', array_unique(array_filter(array_pluck($lists, 'book_uuid'))))->pluck('title','uuid')->toArray();

        foreach ($lists as $k =>$v ){
            $v->book_title = $bookData[$v->book_uuid] ?? '未找到书本信息';
        }

        // 敏感词提醒
        if (!empty($sensitiveArray)) {
            SysSensitive::setReplace('red');
            $checkSensitive = SysSensitive::handerText($sensitiveArray);
            foreach ($checkSensitive as $sensKey => $sensValue) {
                if ($sensValue['replace_count'] > 0) {
                    foreach ($lists as $k=>$v) {
                        if ($v->id == $sensValue['id']) {
                            $v->content = $sensValue['replace_str'];
                        }
                    }
                }
            }
        }
        return $lists;
    }

    /**
     * @param $bookUuid
     * @param int $limit
     * @return array
     */
    public function bookComment ($bookUuid, $limit = 8) {
        if (empty($bookUuid)) {
            return [];
        }
        return Comment::where('book_uuid', $bookUuid)->where('status', self::STATUS_NO)->take($limit)->select('id','name','content', 'create_time')->get()->toArray();
    }
}