<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysThumbnailRule extends Model
{
    const SCOPE_SHOP_AVATAR  = 1; //店铺头像
    const SCOPE_SERVICE_INFO = 2; //服务信息
    const SCOPE_CASE_INFO    = 4; //案例信息
    const SCOPE_TEAM_AVATAR  = 8; //团队成员头像

    public $timestamps = false;

    protected $table    = 'sys_thumbnail_rule';
    protected $fillable = [
        'id', 'name', 'width', 'height', 'scope'
    ];

    /**
     * 获取缩略图规则
     *
     * @param int $scope
     * @author dch
     * @return Model|null|static
     */
    public function getRule($scope = 0)
    {
        return $this->where('scope', '&', $scope)->get();
    }

    /**
     * 转换缩略图
     *
     * @param string $url
     * @param string $ruleName
     * @return mixed
     * @author dch
     */
    public function convertThumbnail($url, $ruleName)
    {
        if(empty($url)){
            return '';
        }
        if(empty($ruleName)){
            return $url;
        }
        return preg_replace('~(\.\w+)$~Uis', "_{$ruleName}$1", $url);
    }

}
