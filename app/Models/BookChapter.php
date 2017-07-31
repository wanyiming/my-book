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
class BookChapter extends Model
{
    protected $table = 'book_chapter';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    const VIP_STATUS = 2;  // 收费
    const MF_STATUS = 1; // 免费

    public function saveData ($newData, $id = '') {
        if (empty($newData)) {
            return response_error();
        }
        if (empty($newData['title'])) {
            return response_error('请输入章节标题');
        }
        if (empty($newData['content'])) {
            return response_error('请输入章节内容');
        }
        if (empty($newData['book_uuid'])) {
            return response_error('请选择书本保存章节信息');
        }
        $hasBook = Books::where('uuid', $newData['book_uuid'])->exists();
        if (empty($hasBook)) {
            return response_error('需要保存的书本信息不存在');
        }
        $hasTrue = false;
        if (empty($id)) {
            $newData['uuid'] = Uuid::uuid4();
            $hasTrue = self::insertGetId($newData);
        } else {
            $hasTrue = self::where('id',intval($id))->update($newData);
        }
        if ($hasTrue) {
            return response_success(['url'=>to_route('admin.books.chapter',['uuid' => $newData['book_uuid']])],'操作成功');
        }
        return response_error('保存失败');
    }
}
