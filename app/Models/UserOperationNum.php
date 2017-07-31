<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOperationNum extends Model
{
    protected $table = 'user_operation_num';
    protected $guarded = ['id'];
    public static $snakeAttributes = false;   // 设置关联模型在打印输出的时候是否自动转为蛇型命名

    // 设置类型
    const TYPE_FOR_SELLER_BID_TENDER = 1;   // 卖家投标
    const TYPE_FOR_ADMIN_GIVE_SELLER_BID_NUM = 2; // 后台赠送投标次数给卖家

    // 设置状态
    const STATUS_ON = 1; // 正常使用中
    const STATUS_OFF = 2; // 不能使用

    // 设置验证状态码
    public static function setCheckResultCode($code_id = ''){
        $code = [
            200 => '验证通过',
            201 => '请求异常',
            202 => '投标次数不足',
            203 => '您还没有投标权限，如有需要请联系 400-138-6066',
            204 => '问问币余额不足',
        ];
        return $code[$code_id] ?? $code;
    }

    // 设置可投标的用户类型
    public static function setBidTenderUserType(){
        $type_arr = [
            User::MANUFACTOR,
            User::DEALER
        ];
        return $type_arr;
    }

    // 获取赠送次数信息
    public static function getGiveInfoArr($type_name = ''){
        $model = new SysSetting();
        $arr = $model->getValue('USER_BID_NUM');
        return $arr[$type_name] ?? $arr;
    }

    /**
     * 获取剩余投标次数
     * @param string $userUUID
     * @return int
     */
    public function getTenderNum ($userUUID = '') {
        $userUUID = $userUUID ? $userUUID : get_user_session_info('user_uuid');
        return (int) self::where('user_uuid',$userUUID)->where('status', self::STATUS_ON)->value('num');
    }


    public static function getTypeArray($type_id = ''){
        $type_arr = [
            self::TYPE_FOR_SELLER_BID_TENDER => '卖家投标',
            self::TYPE_FOR_ADMIN_GIVE_SELLER_BID_NUM => '后台赠送投标次数给卖家'
        ];
        return $type_arr[$type_id] ?? $type_arr;
    }

    /**
     * 检查用户是否可以投标
     * @param $type 用户类型
     * @param string $userUUID 用户uuid
     * @return int 返回状态码
     */
    public static function checkUserJurisdiction($type,$userUUID = ''){
        if(trim($userUUID) === ''){
            $userUUID = get_user_session_info('user_uuid');
        }
        // 根据$user_uuid获取用户类型
        $userType = UserAssociation::where(['uuid'=>$userUUID])->value('type');

        // 获取可投标用户类型
        $typeArr = array_keys(self::getTypeArray());
        if(!in_array($type,$typeArr)){
            return 201; // 请求异常
        }
        $where['user_uuid'] = $userUUID;
        $where['type'] = $type;
        $status = self::STATUS_ON;
        // 如果用户类型不属于可投标的用户类型，仍会添加数据，但是状态为不能使用
        if(!in_array($userType,self::setBidTenderUserType())){
            $status = self::STATUS_OFF;
        }
        $where['status'] = $status;
        $userOpInfo = self::where($where)->first();
        if(empty($userOpInfo)){
            // 如果该用户为初次使用投标功能，初始化一条该用户的信息
            // 获取该用户所属类型的初始投标次数
            $where['num'] = self::getGiveInfoArr(self::getUserBidNumKey($userUUID));
            $userOpInfo = self::create($where);
        }
        if($userOpInfo->num == 0){
            return 202; // 投标次数不足
        }
        if(bccomp($userOpInfo->status,self::STATUS_OFF) == 0){
            return 203; // 您还没有投标权限，如有需要请联系 400-138-6066
        }
        return 200; // 验证通过
    }

    // 检查问问币是否充足
    public static function checkUserWWBInfo(int $decrement_num,$user_uuid = ''){
        if(trim($user_uuid) === ''){
            $user_uuid = get_user_session_info('user_uuid');
        }
        $user_wwb_num = self::getUserWWBInfo($user_uuid);
        if(bccomp($decrement_num,$user_wwb_num) == 1){
            return 204;
        }
        return 200;
    }

    // 获取用户问问币余额信息
    public static function getUserWWBInfo($user_uuid = ''){
        if(trim($user_uuid) === ''){
            $user_uuid = get_user_session_info('user_uuid');
        }
        $where['user_uuid'] = $user_uuid;
        $balance = 0;
        $user_wwb_info = UserVirtual::firstOrCreate($where);
        if(!isset($user_wwb_info->balance)){
            $user_wwb_info->balance = $balance;
        }
        return $user_wwb_info->balance;
    }

    // 扣除用户问问币，由于问问币模块写的太简单，只能我再加一层了
    public static function wwb_decrement($op_remark,$wwb_num = 0,$user_uuid = ''){
        if(trim($user_uuid) === ''){
            $user_uuid = get_user_session_info('user_uuid');
        }
        $check_code = self::checkUserWWBInfo((int)$wwb_num,$user_uuid);
        if($check_code != 200){
            return $check_code;
        }
        $userUuid = $user_uuid;
        $type = 2;
        $sum = $wwb_num;
        $remark = $op_remark;
        $result = UserVirtualIncome::addIncomeWWB($userUuid,$type,$sum,$remark);
        if($result){
            return 200;
        }
        return 201;
    }

    // 增加用户问问币，由于问问币模块写的太简单，只能我再加一层了
    public static function wwb_increment($user_uuid,$wwb_num){

    }

    // 操作问问币 + | -问问币
    public static function operationWWB(){

    }

    // 根据用户类型获取该类型对应的调用投标次数的key
    public static function getUserBidNumKey($user_uuid = ''){
        if(trim($user_uuid) === ''){
            $user_uuid = get_user_session_info('user_uuid');
        }
        // 获取user_association表中用户类型(type)
        $user_type = UserAssociation::where(['uuid'=>$user_uuid])->value('type');
        $key = '';
        if ($user_type == User::MEMBER) {
            // 获取用户等级
            $grade = HomeUser::where(['user_uuid' => $user_uuid])->value('grade');
            switch ($grade) {
                case User::GRADE_ONE:
                    $key = 'member_grade_one';
                    break;
                case User::GRADE_TWO:
                    $key = 'member_grade_two';
                    break;
                case User::GRADE_THREE:
                    $key = 'member_grade_three';
                    break;
            }
        } else {
            $grade = $user_type;
            switch ($grade) {
                case User::MANUFACTOR:
                    $key = 'manufactor';
                    break;
                case User::DEALER:
                    $key = 'dealer';
                    break;
            }
        }
        return $key;
    }
}
