<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SysSetting
 * @package App\Model
 * @author dch
 */
class SysSetting extends Model
{
    const VALUE_TYPE_STRING  = 1; //字符串
    const VALUE_TYPE_TEXT    = 2; //文本
    const VALUE_TYPE_IMG_SRC = 3;//图片只包含地址
    const VALUE_TYPE_IMG_TAG = 4;//生成整个img标签
    const VALUE_TYPE_SELECT  = 6;//选择项
    const VALUE_TYPE_ARRAY   = 7;//数组

    /**
     *  招投标附件上传参数信息
     *  max_file_size:10240kb           // 单个文件最大上传大小
     *  max_total_size:512000kb         // 所有文件上传大小
     *  max_total_num:20                // 每天可上传的文件数
     *  mime_types:doc,docx,xls,xlsx    // 可上传文件的类型
     */
    const UPLOAD_TYPE_TAB_ATTACHMENT = 11;  // 文件上传类型 11 招投标附件上传

    public $timestamps   = false;
    public $incrementing = false;

    protected $primaryKey = 'name';
    protected $keyType    = 'string';
    protected $table      = 'sys_setting';
    protected $fillable   = [
        'name',
        'value',
        'label',
        'value_type'
    ];

    public function returnUploadTypeValue($type_id = ''){
        $upload_group = [
            self::UPLOAD_TYPE_TAB_ATTACHMENT => 'tab_attachment_upload'
        ];
        return $upload_group[$type_id] ?? $upload_group[self::UPLOAD_TYPE_TAB_ATTACHMENT];
    }

    public function setValue($name, $value, $type = self::VALUE_TYPE_STRING, $label = '')
    {
        $setting = $this->firstOrCreate(['name' => strtoupper($name)]);
        switch ($type) {
            case self::VALUE_TYPE_STRING:
            case self::VALUE_TYPE_TEXT:
            case self::VALUE_TYPE_IMG_SRC:
                $value = strval($value);
                break;
            case self::VALUE_TYPE_IMG_TAG:
            case self::VALUE_TYPE_ARRAY:
            case self::VALUE_TYPE_SELECT:
                $value = json_encode($value);
                break;
            default: //默认字符串处理
                $value = strval($value);
                break;
        }

        return $setting->fill(['name' => $name, 'value' => $value, 'label' => $label, 'value_type' => $type])->save();
    }

    public function getValue($name = null)
    {
        if (!is_null($name)) {
            $setting = self::where('name', strtoupper($name))->first();
            if (empty($setting)) {
                return null;
            }
            $valueType = $setting['value_type'];
            $value = $setting['value'];
        } else {
            $valueType = $this->getAttribute('value_type');
            $value = $this->getAttribute('value');
        }

        switch ($valueType) {
            case self::VALUE_TYPE_STRING:
            case self::VALUE_TYPE_TEXT:
            case self::VALUE_TYPE_IMG_SRC:
                $ret = strval($value);
                break;
            case self::VALUE_TYPE_IMG_TAG:
                $ret = $this->resolveImgTag($value);
                break;
            case self::VALUE_TYPE_ARRAY:
            case self::VALUE_TYPE_SELECT:
                $ret = json_decode($value, true);
                $ret = empty($ret) ? [] : $ret;
                break;
            default: //默认字符串处理
                $ret = strval($value);
                break;
        }

        return $ret;
    }

    public function getTypes()
    {
        return [
            self::VALUE_TYPE_STRING  => '字符串',
            self::VALUE_TYPE_TEXT    => '文本',
            self::VALUE_TYPE_IMG_SRC => '图片值',
            //self::VALUE_TYPE_IMG_TAG => 'img标签',
            //self::VALUE_TYPE_SELECT  => '选择项',
            self::VALUE_TYPE_ARRAY   => '数组',
        ];
    }

    public function type2cn($valueType = null)
    {
        if (is_null($valueType)) {
            $valueType = $this->getAttribute('value_type');
        }
        switch ($valueType) {
            case self::VALUE_TYPE_STRING:
                return '字符串';
            case self::VALUE_TYPE_TEXT:
                return '文本';
            case self::VALUE_TYPE_IMG_SRC:
                return '图片值';
            case self::VALUE_TYPE_IMG_TAG:
                return '图片标签';
            case self::VALUE_TYPE_SELECT:
                return '选项';
            case self::VALUE_TYPE_ARRAY:
                return '数组';
            default:
                return '文本';
        }
    }

    public function resolveImgTag($value)
    {
        $arr = json_decode($value, true);

        $img['src'] = $arr['src'] ?? '';
        $img['alt'] = $arr['alt'] ?? '';
        $img['width'] = $arr['width'] ?? '';
        $img['title'] = $arr['title'] ?? '';
        $img['height'] = $arr['height'] ?? '';
        if (empty($img['src'])) {
            return '';
        }

        $a['target'] = empty($arr['target']) ? '_blank' : '_self';
        $a['href'] = $arr['href'] ?? '';

        $imgTag = '';
        foreach ($img as $tagAttr => $tagValue) {
            $imgTag .= sprintf(' %s="%s" ', $tagAttr, $tagValue);
        }
        $imgTag = sprintf('<img %s>', $imgTag);

        if (empty($a['href'])) {
            return $imgTag;
        }

        return sprintf('<a href="%s" target="%s">%s</a>', $a['href'], $a['target'], $imgTag);
    }
}
