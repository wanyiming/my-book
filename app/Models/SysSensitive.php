<?php

namespace App\Models;

use App\Libraries\SSO\Exception;
use Illuminate\Database\Eloquent\Model;

class SysSensitive extends Model
{

    protected $table = 'sys_sensitive';

    protected $dateFormat = 'U';
    public $timestamps = false;         //是否有created_at和updated_at字段

    protected $guarded = ['id'];

    const STATUS_NORMAL = 1;//正常
    const STATUS_DISABLE = 2;//禁用
    const STATUS_DELETE = 99;//删除
    const STATUS_ARR = [
        self::STATUS_NORMAL => ['name'=> '正常','class' => LABEL_SUCCESS],
        self::STATUS_DISABLE => ['name' => '禁用' ,'class' => LABEL_DEFAULT],
        self::STATUS_DELETE => ['name' => '删除' ,'class' => LABEL_DELETE],
    ];


    const PAGE_NUM = 10;

    public static $replace = '***';

    const IS_ON_SENSITIVE = true; // 是否开启敏感词过滤 ; false 不开启；

    // 设置文本被替换的内容
    public static function setReplace ($value)
    {
        self::$replace = $value;
    }

    /**
     * 替换关键词
     * @param $textArray [['id' => '', 'text' => '']]; 数组格式
     * @return array  [['id' => '', 'text' => '', 'replace_str' => '', 'replace_word' => '', 'replace_count' => '']]; 文本格式
     *                  id              文本             替换后的文本        被替换了那些词        替换了多少次
     * @throws Exception
     */
    public static function handerText ($textArray) {
        if (empty($textArray)) {
            throw new Exception('参数错误');
        }
        if (false === self::IS_ON_SENSITIVE) { // 关闭了敏感词过滤
            foreach ($textArray as $k=>$v) {
                $textArray[$k]['replace_str'] = $v['text'];
                $textArray[$k]['replace_word'] = [];
                $textArray[$k]['replace_count'] = 0;
            }
            return  $textArray;
        }
        if (count($textArray)==count($textArray, 1)) {
            $textArray = [$textArray];
        }
        // 保持下标一致
        $textArray = (array_values($textArray));
        $sensiviteArray = array_pluck($textArray,'text');
        if (empty($sensiviteArray)) {
            throw new  Exception('过滤敏感词参数格式错误！');
        }
        // 随机生成一个签名
        $signature = '|'.random_char().'|';
        $resultArray = ensitive_word_filtering(implode($signature, $sensiviteArray), self::$replace, true);

        // 被替换后的文本
        $newReplaceText = explode($signature, $resultArray['subject']);

        // 替换了那些词
        $newReplaceWord = explode('###', $resultArray['replace_str']);

        foreach ($textArray as $k=>$v) {
            // 替换之后的结果
            $textArray[$k]['replace_str'] = $newReplaceText[$k];
            $replaceWoed = [];
            $replaceCount = 0;
            foreach ($newReplaceWord as $value) {
                if(stripos($v['text'], $value) !== false ){
                    $replaceWoed[] = $value;
                    $replaceCount ++;
                }
            }
            // 替换了那些敏感词
            $textArray[$k]['replace_word'] = implode(',', $replaceWoed);
            // 替换了多少次
            $textArray[$k]['replace_count'] = $replaceCount;
        }
        return $textArray;
    }
}
