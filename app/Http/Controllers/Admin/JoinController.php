<?php
namespace App\Http\Controllers\Admin;

use App\Models\JoinApply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * 加盟留言
 * Class JoinController
 * @package App\Http\Controllers\Admin
 */
class JoinController extends Controller
{
    public function index(){
        $where[] = ['status','!=',99];
        $list = JoinApply::where($where)->orderBy('status','asc')->paginate();
        $status_group = JoinApply::getStatusGroup();
        foreach ($list as $value){
            $value->status_cn = $status_group[$value->status] ?? '异常状态';
        }
        $type_group = JoinApply::getTypeGroup();
        return view('admin.join.index',compact('list','type_group'));
    }

    public function change_status(Request $request){
        $id = $request->input('id','');
        if(!isInteger($id)){
            return response_error('请求异常');
        }
        $type = $request->input('type','');
        if(!isInteger($type) || !in_array($type,[JoinApply::STATUS_READE_YES,JoinApply::STATUS_READE_DEL])){
            return response_error('请求异常');
        }
        $where['id'] = $id;
        if($type == JoinApply::STATUS_READE_YES){
            $where['status'] = JoinApply::STATUS_READE_NOT;
        }else if($type == JoinApply::STATUS_READE_DEL){
            $where[] = ['status','!=',JoinApply::STATUS_READE_NOT];
        }
        $info = JoinApply::where($where)->first();
        if(empty($info)){
            return response_error('信息不存在或当前状态不可操作');
        }
        $result = JoinApply::where($where)->update(['status'=>$type]);
        if($result){
            return response_message('操作成功');
        }
        return response_error('操作失败');
    }
}
