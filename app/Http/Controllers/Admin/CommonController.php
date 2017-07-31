<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    protected $table = null;
    protected $jump_url = null;

    function __construct()
    {
    }
    /**
     * 设置数据启用禁用状态
     */
    public function setStatus(Request $request){
        $id = $request ->get('id');
        if (empty($id)) return json_encode(['status' => 400,'info'=>'参数错误，请刷新页面重试！']);

        //验证数据是否存在
        $info = DB::table($this->table)->find($id);
        if (!$info) return json_encode(['status' => 400,'info' => '数据信息不存在，请刷新页面重试！']);

        switch($info->status){
            case 1://启用时执行禁用操作
                $msg = '禁用成功';
                $errMsg = '禁用失败';
                $result = DB::table($this->table)->where(['id' => $id]) -> update(['status' => 2]);
                break;
            case 2://禁用时执行启用操作
                $msg = '启用成功';
                $errMsg = '启用失败';
                $result = DB::table($this->table)->where(['id' => $id]) -> update(['status' => 1]);
                break;
            default:
                $result = 0;
                $msg = '';
                break;
        }
        if ($result){
            return json_encode(['status' => 200,'info' => $msg,'url' =>'']);
        }else {
            return json_encode(['status' => 400,'info' => $errMsg]);
        }
    }

    /**
     * 删除数据(物理删除)
     */
    protected function wDel(Request $request){
        $id = $request ->get('id');
        if (empty($id)) return json_encode(['status' => 400,'info'=>'参数错误，请刷新页面重试！']);
        //验证数据是否存在
        $info = DB::table($this->table)->find($id);
        if (!$info) return json_encode(['status' => 400,'info' => '数据信息不存在，请刷新页面重试！']);

        $result = DB::table($this->table)->where(['id' => $id])->delete();
        if ($result){
            return json_encode(['status' => 200,'info' => '删除成功','url' =>'']);
        }else {
            return json_encode(['status' => 400,'info' => '连接服务器失败，请稍后再试']);
        }
    }

    /**
     * 删除(逻辑删除)
     */
    protected function lDel(Request $request){
        $id = $request ->get('id');
        if (empty($id)) return json_encode(['status' => 400,'info'=>'参数错误，请刷新页面重试！']);
        //验证数据是否存在
        $info = DB::table($this->table)->find($id);
        if (!$info) return json_encode(['status' => 400,'info' => '数据信息不存在，请刷新页面重试！']);

        $result = DB::table($this->table)->where(['id' => $id])->update(['status' => 99]);
        if ($result){
            return json_encode(['status' => 200,'info' => '删除成功','url' =>'']);
        }else {
            return json_encode(['status' => 400,'info' => '连接服务器失败，请稍后再试']);
        }
    }

    /**
     * 数据还原
     */
    protected function reBack(Request $request){
        $id = $request ->get('id');
        if(empty($id)){
            return json_encode(['status' => 400,'info' => '参数错误，请刷新页面重试']);
        }
        //验证数据是否存在
        $info = DB::table($this->table)->find($id);
        if(!$info){
            return json_encode(['status' => 400,'info' => '数据信息不存在，请刷新页面重试']);
        }
        $result = DB::table($this->table)-> where('id','=',$id)->update(['status' => 1]);

        if($result){
            return json_encode(['status' => 200,'info' => '还原数据成功','url' => '']);
        }else{
            return json_encode(['status' => 400,'info' => '连接服务器失败，请稍后再试']);
        }
    }

}
