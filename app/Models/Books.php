<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 *
 * @author  liufangyuan
 * Class Collection
 * @package App\Models
 */
class Books extends Model
{
    protected $table = 'book';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    const STATUS_OFF = 2;  // 暂停
    const STATUS_ON = 1; // 启用
    const STATUS_DEL = 99; // 删除

    const TYPE_OVER = 1; // 完本
    const TYPE_SERIAL = 2; // 连载

    const STATUS_ALL = [
        self::STATUS_ON => ['name'=>'正常','class'=>LABEL_SUCCESS],
        self::STATUS_OFF => ['name'=>'停用','class'=>LABEL_DEFAULT],
        self::STATUS_DEL => ['name'=>'删除','class'=>LABEL_DELETE],
    ];

    const TYPES_ALL = [
        self::TYPE_OVER => [ 'name' =>  '完本', 'class' => LABEL_DEFAULT],
        self::TYPE_SERIAL => [ 'name' => '连载', 'class' => LABEL_INFO]
    ];

    /**
     * 保存信息
     * @param $saveData
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveBook ($saveData , $id) {
        if (empty($id)) {
            $saveData['uuid'] = Uuid::uuid4();
            $isTrue = self::insertGetId($saveData);
        } else {
            $isTrue = self::where('id', intval($id))->update($saveData);
        }
        if (empty($isTrue)) {
            return response_error('保存失败 ');
        }
        return response_message('保存成功');
    }


    /**
     * 得到首页推荐的数据信息
     * @param $limit
     * @return array|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function bookData ($limit) {
        $recommendObjectId = (new Recommend())->getObjectId(Recommend::OBJECT_TYPE_BOOK, Recommend::OBJECT_POSITION_HOME);
        if (empty($recommendObjectId) || count($recommendObjectId) < $limit) {
            $account = $limit - count($recommendObjectId);
            // 被推荐的
            $recommendData = self::whereIn('id', $recommendObjectId)->where('status', self::STATUS_ON)->select('id','title','book_cover', 'author')->take($limit)->get()->toArray();
            // 剩余补上的
            $trapData = self::where('status', self::STATUS_ON)->orderBy('read_num', 'desc')->select('id','title','book_cover', 'author')->take($account)->get()->toArray();
            return array_merge($recommendData ?? [], $trapData ?? []);
        }
        return self::whereIn('id', $recommendObjectId)->where('status', self::STATUS_ON)->select('id','title','book_cover', 'author')->take($limit)->get()->toArray();
    }

    /**
     * 返回最新或者需要推荐的数据
     * @param string $typeUuid 分类id
     * @param string $orderFiled 排序字段
     * @param int $limit 条数
     * @return \Illuminate\Support\Collection
     */
    public function orderData ($typeUuid = 'all',$orderFiled = 'update_time', $limit = 10, $office = 1) {
        return self::where(function ($query) use ($typeUuid) {
            if ($typeUuid != 'all') {
                $query->where('book_type', $typeUuid);
            }
        })->where('status', Books::STATUS_ON)->select('id','title','author','book_type','recom_num','read_num','recoll_num','update_fild', 'book_type', 'book_cover', 'type_id', 'profiles')
            ->orderBy($orderFiled, 'desc')->forPage($office, $limit)->get();
    }

	
    public function orderDataTotal ($typeUuid = 'all') {
        return self::where(function ($query) use ($typeUuid) {
            if ($typeUuid != 'all') {
                $query->where('book_type', $typeUuid);
            }
        })->count();
    }



    /**
     * 推荐的票
     * @param $id
     * @param string $filed
     * @return bool
     */
    public function setReadingNum ($id, $filed = 'read_num') {
        if (empty($id)) {
            return false;
        }
        self::where('id', intval($id))->increment('read_num');
    }


    public function getBookInfo ($bookId) {
        if (empty($bookId)) {
            return [];
        }
        return Books::where('id', intval($bookId))->where('status', Books::STATUS_ON)->first()->toArray();
    }
}
