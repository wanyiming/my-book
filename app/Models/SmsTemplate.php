<?php
/**
 * Class 短信Model
 * @method
 * @package App
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    const STATUS_NORMAL = 1;// 正常
    const STATUS_DISABLE = 2;// 禁用
    const STATUS_DELETE = 99;// 删除
    const STATUS_ARR = [
        self::STATUS_NORMAL => ['name'=> '启用','class' => LABEL_SUCCESS],
        self::STATUS_DISABLE => ['name' => '禁用' ,'class' => LABEL_DEFAULT],
        self::STATUS_DELETE => ['name' => '删除' ,'class' => LABEL_DELETE],
    ];

    protected $table = 'sms_template';

    public $timestamps = false;

    protected $dateFormat = 'U';

    protected $guarded = ['id'];

    protected $fillable = [
        'title', 'content', 'remark', 'status', 'typeid', 'client_base', 'created_at'
    ];

    // 获取模板状态信息
    public static function returnStatusInfo($status = ''){
        $status_group = [
            self::STATUS_NORMAL => '正常',
            self::STATUS_DISABLE => '禁用',
            self::STATUS_DELETE => '已删除',
        ];
        return $status_group[$status] ?? $status_group;
    }

    public static function getTemplateIdAndTitle(){
        $list = self::whereIn('status',[self::STATUS_NORMAL])->pluck('title','id');
        return $list;
    }

    /**
     * 调用静态模板
     * @param string $callKey
     * @return string
     */
    public function getSmsTemplate($callKey = '', $parameter = []) {
        if (empty($callKey)) {
            return '';
        }
        if (\Cache::has('smsTemplate')) {
            $content =  \Cache::get('smsTemplate')[$callKey] ?? '';
        } else {
            $content = $this->where('call_key',$callKey)->where('status',self::STATUS_NORMAL)->value('content');
        }
        if (empty($content)) {
            return '';
        }
        try {
            $parameterKey = [];
            $parameterValue = [];
            foreach ($parameter as $k=>$v) {
                if (!empty($k)) {
                    $parameterKey [] = sprintf("/{%s}/", $k);
                    $parameterValue[] = $v;
                }
            }
            return preg_replace($parameterKey, $parameterValue, $content, 1) . ' 回复T退订';
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
        }
        return $content;
    }
}
