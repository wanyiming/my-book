<?php
/*
 * 权限验证类
 */
namespace App\Models;

use App\Libraries\Extend\Tree;
use Illuminate\Database\Eloquent\Model;
use App\Models\SysRole;
use App\Models\SysAdmin;
use App\Models\SysNode;
/**
 * App\Models\Jurisdiction
 *
 * @mixin \Eloquent
 */
class Jurisdiction extends Model
{
    /**
     * 根据用户id获取用户组,返回值为数组
     * @param  uid int     用户id
     * @return array       用户所属的用户组 array(
     *                                         array('uid'=>'用户id','group_id'=>'用户组id','title'=>'用户组名称','rules'=>'用户组拥有的规则id,多个,号隔开'),
     *                                         ...)
     */
    public function getGroups($uid) {
        static $groups = array();
        if (isset($groups[$uid]))
            return $groups[$uid];
        //获取用户所属部门
        $where['id'] = $uid;
        $dp_info = SysAdmin::where($where)->select('role_id','department_id')->first();
        $dp_arr = $dp_info->toArray();
        if($dp_arr['department_id'] == 0 && $dp_arr['role_id'] == 0){
            $return['info'] = '无效角色！！！请联系管理员';
            return $return;
        }
        //查询根据部门id查询对应的权限,如果是超级管理员，则为-1
        $authority = SysRole::where(['id' => $dp_arr['role_id']])->value('authority');

        $isSuperAdmin = false;
        //检测该角色是否为超级管理员
        if($dp_arr['role_id'] != 0){
            $roleInfo =  SysRole::where(['id'=>$dp_arr['role_id']])->first();
            $role_info_arr = empty($roleInfo) ? [] : $roleInfo->toArray();
            if(isset($role_info_arr['authority']) && ($role_info_arr['authority'] == '-1')){
                $isSuperAdmin = true;
            }
        }
        $return['isSuperAdmin'] = $isSuperAdmin;
        $return['rules'] = $authority;
        return $return;
    }

    /**
     * 获得权限列表
     * @param $uid
     * @param bool $type
     * @return mixed
     * @User: long-yun-rui
     */
    static public function getAuthList($uid,$type = false) {
        $node_list = self::get_old_AuthList($uid,$type);

//        print_r($node_list);die;
        $Tree = Tree::getInstance($node_list, array('id', 'pid'), 0, true);
        $return['node_list'] = $Tree->leaf(1);
        return $return;
    }

    /**
     * 获得用户资料,根据自己的情况读取数据库
     */
    protected function getUserInfo($uid) {
        static $userinfo=array();
        if(!isset($userinfo[$uid])){
            $user_info = SysAdmin::where(array('id'=>$uid))->first();
            $userinfo[$uid] = $user_info->toArray();
        }
        return $userinfo[$uid];
    }

    /**
     * 获得权限列表----左侧导航栏使用
     * @param integer $uid  用户id
     */
    protected function get_old_AuthList($uid,$type = false) {
        static $_authList = array(); //保存用户验证通过的权限列表
        //读取用户所属用户组
        $groups = $this->getGroups($uid);
        $ids = array();//保存用户所属用户组设置的所有权限规则id
        $ids = array_merge($ids, explode(',', trim($groups['rules'], ',')));
        $ids = array_unique($ids);
        if (empty($ids)) {
            $_authList[$uid] = array();
            return array();
        }

        //读取用户组所有有效权限规则
        $return = [];
        if($groups['isSuperAdmin']===true){
            $rules =  SysNode::select('id','route','name','condition','pid','status','class_name')->get();
        }else{
            $rules =  SysNode::whereIn('id',(array)$ids)->select('id','code','name','condition','pid','status','class_name')->get();
        }
        $new_rules = $rules->toArray();
        $node_list = array();
        foreach ($new_rules as $val) {
//            if ($val['status'] != -1) {
            if ($val['status'] == 1) {
                $node_list[] = $val;
            }
            if (true === $type and !empty($val['code'])) {
                $code = strtolower($val['code']);
                $return['node_url'][$code] = true;
            }
        }
        return $node_list;
    }


    /*
     * 获得权限列表---权限验证使用
     * @param integer $uid  用户id
     */
    /**
     *
     * @param $uid
     * @param bool $type
     * @return array
     * @User: long-yun-rui
     */
    protected function get_this_AuthList($uid, $type = false) {
        static $_authList = array(); //保存用户验证通过的权限列表
        //读取用户所属用户组
        $groups = $this->getGroups($uid);
        $ids = array();//保存用户所属用户组设置的所有权限规则id
        $ids = array_merge($ids, explode(',', trim($groups['rules'], ',')));
        $ids = array_unique($ids);
        if (empty($ids)) {
            $_authList[$uid] = array();
            return array();
        }

        //读取用户组所有有效权限规则
        $return = [];
        if($groups['isSuperAdmin']===true){
            $rules =  SysNode::select('id','code','name','condition','pid','status','class_name')->get();
        }else{
            $rules =  SysNode::whereIn('id',(array)$ids)->select('id','code','name','condition','pid','status','class_name')->get();
        }
        $new_rules = $rules->toArray();
        $node_list = array();
        foreach ($new_rules as $val) {
            if ($val['status'] != -1) {
//            if ($val['status'] == 1) {
                $node_list[] = $val;
            }
            if (true === $type and !empty($val['code'])) {
                $code = strtolower($val['code']);
                $return['node_url'][$code] = true;
            }
        }
        return $node_list;
    }
}
