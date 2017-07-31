<?php

namespace App\Http\Controllers\Member;

use App\Models\SmsCount;
use App\Models\SmsLog;
use App\Models\SmsNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class LetterController extends Controller
{
    public function index(Request $request)
    {
        \SEO::setTitle('系统通知');
        $user_uuid = get_user_session_info('user_uuid');
        $get = $request->input();
        $where['user_uuid'] = $user_uuid;
        $where[] = ['status','!=',SmsNotification::READ_STATUS_DEL];
        $where[] = ['sms_type','=',SmsLog::SMS_TYPE_NOTIFY];
        if (isset($get['title']) && trim($get['title']) != '') {
            $where[] = ['title', 'LIKE', '%' . trim($get['title']) . '%'];
        }
        $list = SmsNotification::where($where)->with(
            'get_letter_info'
        )->orderBy('status','asc')->orderBy('id','desc')->paginate();

        // 获取未读消息对应的状态
        $read_no_status = SmsNotification::READ_STATUS_NO;
        return view('member.letter.index', compact('list', 'get', 'read_no_status'));
    }

    public function change_status(Request $request){
        $id_str = $request->input('_id_str','');
        $id_arr = explode(',',$id_str);
        if($id_str == '' || empty($id_arr)){
            return response_error('请选择需要操作的信息');
        }
        $type = $request->input('type',1);
        $user_uuid = get_user_session_info('user_uuid');
        if($type == 1){
            // 如果是标记为已读
            $save_data['status'] = SmsNotification::READ_STATUS_YES;
            $return_info = '标记成功';
        }else{
            $save_data['status'] = SmsNotification::READ_STATUS_DEL;
            $return_info = '删除成功';
        }
        $list = SmsNotification::whereIn('id',$id_arr)->where(['user_uuid'=>$user_uuid,['status','!=',$save_data['status']]])->whereNotIn('status',[SmsNotification::READ_STATUS_DEL])->get();
        if($list->isEmpty()){
            return response_error('请求的信息不存在或信息状态不可进行当前操作');
        }
        DB::beginTransaction();
        // 如果是删除的时候还要检查被删除的信息是否是已经查看过的
        if($type == 2){
            $has_read_result = SmsNotification::whereIn('id',$id_arr)->where(['user_uuid'=>$user_uuid,['status','!=',$save_data['status']]])->whereIn('status',[SmsNotification::READ_STATUS_YES])->update($save_data);
            $not_read_result = SmsNotification::whereIn('id',$id_arr)->where(['user_uuid'=>$user_uuid,['status','!=',$save_data['status']]])->whereIn('status',[SmsNotification::READ_STATUS_NO])->update($save_data);
            $result = bcadd($has_read_result,$not_read_result);
            $decrement_num = $not_read_result;
        }else{
            $result = SmsNotification::whereIn('id',$id_arr)->where(['user_uuid'=>$user_uuid,['status','!=',$save_data['status']]])->whereNotIn('status',[SmsNotification::READ_STATUS_DEL])->update($save_data);
            $decrement_num = $result;
        }
        if($result){
            SmsCount::where(['user_uuid'=>$user_uuid,['mail_count','>',0]])->decrement('mail_count',$decrement_num);
            DB::commit();
            return response_message($return_info);
        }
        DB::rollBack();
        return response_error('操作失败');
    }

    public function trade_index(Request $request)
    {
        \SEO::setTitle('交易提醒');
        $user_uuid = get_user_session_info('user_uuid');
        $get = $request->input();
        $where['user_uuid'] = $user_uuid;
        $where[] = ['status','!=',SmsNotification::READ_STATUS_DEL];
        $where[] = ['sms_type','=',SmsLog::SMS_TYPE_TRADE];
        if (isset($get['title']) && trim($get['title']) != '') {
            $where[] = ['title', 'LIKE', '%' . trim($get['title']) . '%'];
        }
        $list = SmsNotification::where($where)->with(
            'get_letter_info'
        )->orderBy('status','asc')->orderBy('id','desc')->paginate();

        // 获取未读消息对应的状态
        $read_no_status = SmsNotification::READ_STATUS_NO;
        return view('member.letter.trade_index', compact('list', 'get', 'read_no_status'));
    }

    public function trade_change_status(Request $request){
        $id_str = $request->input('_id_str','');
        $id_arr = explode(',',$id_str);
        if($id_str == '' || empty($id_arr)){
            return response_error('请选择需要操作的信息');
        }
        $type = $request->input('type',1);
        $user_uuid = get_user_session_info('user_uuid');
        if($type == 1){
            // 如果是标记为已读
            $save_data['status'] = SmsNotification::READ_STATUS_YES;
            $return_info = '标记成功';
        }else{
            $save_data['status'] = SmsNotification::READ_STATUS_DEL;
            $return_info = '删除成功';
        }
        $list = SmsNotification::whereIn('id',$id_arr)->where(['user_uuid'=>$user_uuid,['status','!=',$save_data['status']]])->whereNotIn('status',[SmsNotification::READ_STATUS_DEL])->get();
        if($list->isEmpty()){
            return response_error('请求的信息不存在或信息状态不可进行当前操作');
        }
        DB::beginTransaction();
        // 如果是删除的时候还要检查被删除的信息是否是已经查看过的
        if($type == 2){
            $has_read_result = SmsNotification::whereIn('id',$id_arr)->where(['user_uuid'=>$user_uuid,['status','!=',$save_data['status']]])->whereIn('status',[SmsNotification::READ_STATUS_YES])->update($save_data);
            $not_read_result = SmsNotification::whereIn('id',$id_arr)->where(['user_uuid'=>$user_uuid,['status','!=',$save_data['status']]])->whereIn('status',[SmsNotification::READ_STATUS_NO])->update($save_data);
            $result = bcadd($has_read_result,$not_read_result);
            $decrement_num = $not_read_result;
        }else{
            $result = SmsNotification::whereIn('id',$id_arr)->where(['user_uuid'=>$user_uuid,['status','!=',$save_data['status']]])->whereNotIn('status',[SmsNotification::READ_STATUS_DEL])->update($save_data);
            $decrement_num = $result;
        }
        if($result){
            SmsCount::where(['user_uuid'=>$user_uuid,['trade_count','>',0]])->decrement('trade_count',$decrement_num);
            DB::commit();
            return response_message($return_info);
        }
        DB::rollBack();
        return response_error('操作失败');
    }
}
