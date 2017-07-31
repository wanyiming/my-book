<?php

namespace App\Http\Controllers\Member;

use App\Facades\SEO;
use App\Models\DxCaptcha;
use App\Models\PubArea;
use App\Models\SysSetting;
use App\Models\TabAttachment;
use App\Models\TabBid;
use App\Models\TabTender;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class TenderController extends Controller
{
    public function getUserUuid()
    {
        return get_user_session_info('user_uuid');
    }

    public function index(Request $request)
    {
        \SEO::setTitle('招标管理');
        // 获取所有状态值
        $whereIn = TabTender::returnStatusGroup();
        // 获取所有状态组
        $statusGroupInfo = TabTender::getStatusGroup();
        $user_uuid = get_user_session_info('user_uuid');
        $get = $request->input();
        $where = [];
        $where['user_uuid'] = $user_uuid;
        if (isset($get['id']) && $get['id']) {
            $where[] = ['id', '=', $get['id']];
        }
        if (isset($get['link_man']) && $get['link_man']) {
            $where[] = ['link_man', 'like', $get['link_man'] . '%'];
        }
        if (isset($get['link_mobile']) && $get['link_mobile']) {
            $where[] = ['link_mobile', '=', $get['link_mobile']];
        }
        if (isset($get['title']) && $get['title']) {
            $where[] = ['title', 'like', $get['title'] . '%'];
        }
        if (isset($get['has_set_wwb']) && $get['has_set_wwb']) {
            $where[] = ['has_set_wwb', '=', $get['has_set_wwb']];
        }
        if (isset($get['status']) && $get['status']) {
            // 根据选择的状态组获取状态组下的状态值
            $whereIn = TabTender::returnStatusGroup($get['status']);
        }
        if (isset($get['has_tj']) && $get['has_tj']) {
            $where[] = ['has_tj', '=', $get['has_tj']];
        }
        $orderBy = 'id';
        $direction = 'desc';
        if (isset($get['order_direction']) && $get['order_direction']) {
            if ($get['order_direction'] == 1) {
                $orderBy = 'bid_num';
                $direction = 'desc';
            } else if ($get['order_direction'] == 2) {
                $orderBy = 'bid_num';
                $direction = 'asc';
            }

        }

        // 招标中
        $totalSql = '
                SUM(CASE 
                    WHEN `status` IN(%s) AND user_uuid = "%s"  THEN 1
                    ELSE 0
                END) AS %s';


        $tender_status_group_pass = TabTender::TENDER_STATUS_GROUP_PASS;
        $tender_status_group_end = TabTender::TENDER_STATUS_GROUP_END;
        $tender_status_group_pending = TabTender::TENDER_STATUS_GROUP_PENDING;
        $tender_status_group_refuse = TabTender::TENDER_STATUS_GROUP_REFUSE;
        $tender_status_group_del = TabTender::TENDER_STATUS_GROUP_DEL;

        $totalResult = TabTender::select(
            DB::raw(sprintf($totalSql, implode(',', TabTender::returnStatusGroup($tender_status_group_pass)), $user_uuid, 'total_pass')),
            DB::raw(sprintf($totalSql, implode(',', TabTender::returnStatusGroup($tender_status_group_end)), $user_uuid, 'total_end')),
            DB::raw(sprintf($totalSql, implode(',', TabTender::returnStatusGroup($tender_status_group_pending)), $user_uuid, 'total_pending')),
            DB::raw(sprintf($totalSql, implode(',', TabTender::returnStatusGroup($tender_status_group_refuse)), $user_uuid, 'total_refuse')),
            DB::raw(sprintf($totalSql, implode(',', TabTender::returnStatusGroup($tender_status_group_del)), $user_uuid, 'total_del'))
        )->first();

        $list = TabTender::getTenderList($where, 'status', $whereIn, 10, $orderBy, $direction);
        $data_reason = '';
        foreach ($list as $value) {
            $value->status_cn = TabTender::getStatusGroup($value->status);

            if($value->reason){
                $data_reason = implode(',',json_decode($value->reason,true));
            }
            $value->btn_html = $this->returnBtnHtml($value->status, $value->id,$data_reason);
            $value->status_html = $this->returnStatusInfoHtml($value->status, $value->status_cn);
        }

        return view('member.tender.index', compact(
            'list', 'statusGroupInfo', 'get', 'totalResult',
            'tender_status_group_pass',
            'tender_status_group_end',
            'tender_status_group_pending',
            'tender_status_group_refuse',
            'tender_status_group_del'
        ));
    }

    public function add()
    {
        \SEO::setTitle('发布招标');
        $default_mobile = (new TabTender())->getUserRegMobile();
        // 获取附件参数
        $attachment_param = (new SysSetting())->getValue('tab_attachment_upload');
        $upload_type = SysSetting::UPLOAD_TYPE_TAB_ATTACHMENT;
        return view('member.tender.add', compact('default_mobile', 'attachment_param', 'upload_type'));
    }

    public function edit(int $id)
    {
        \SEO::setTitle('发布招标');
        // 只有审核未通过和没有人投标的时候可以修改招标信息
        $whereIn = [TabTender::TENDER_STATUS_FAIL, TabTender::TENDER_STATUS_SUCCESS];
        $info = TabTender::with(
            [
                'attachment_list' => function ($query) {
                    $query->where(['type' => TabAttachment::ATTACHMENT_TYPE_TENDER])->with('attachment_info');
                }
            ]
        )->where(['id' => $id, 'user_uuid' => $this->getUserUuid()])->whereIn('status', $whereIn)->first();
        if (empty($info)) {
            return error_show_msg('信息不存在或信息状态不可进行审核操作，请刷新后再试');
        }
        $address_arr = (new PubArea())->id2tree($info->address_id);
        // 获取注册时使用的手机号码
        $default_mobile = (new TabTender())->getUserRegMobile();

        // 获取当前信息使用的手机号码
        $now_mobile = $info->link_mobile;
        // 如果该信息的联系电话是默认的手机号码，就不显示
        if ($default_mobile == $now_mobile) {
            $mobile_radio = 1;  // 使用注册绑定手机号码
        } else {
            $mobile_radio = 3;  // 使用非注册绑定手机号码
        }
        // 如果是使用的与注册时绑定的手机号码不同，则需要多显示一条原联系方式

//        print_r($info->toArray());die;
        // 获取附件参数
        $attachment_param = (new SysSetting())->getValue('tab_attachment_upload');
        $upload_type = SysSetting::UPLOAD_TYPE_TAB_ATTACHMENT;
        return view('member.tender.add', compact('info', 'address_arr', 'default_mobile', 'mobile_radio', 'attachment_param', 'upload_type'));
    }


    public function info(int $id)
    {

        // 只有招标中和已结束状态的招标信息能查看详情
        $whereIn = array_merge(TabTender::returnStatusGroup(TabTender::TENDER_STATUS_GROUP_PASS), TabTender::returnStatusGroup(TabTender::TENDER_STATUS_GROUP_END));
        $info = TabTender::with(
            [
                'attachment_list' => function ($query) {
                    $query->where(['type' => TabAttachment::ATTACHMENT_TYPE_TENDER])->with('attachment_info');
                }
            ]
        )->where(['id' => $id, 'user_uuid' => $this->getUserUuid()])->whereIn('status', $whereIn)->first();
        if (empty($info)) {
            return error_show_msg('信息不存在或信息状态不可进行操作，请刷新后再试', route('member.tender.index'));
        }

        $address_arr = (new PubArea())->id2tree($info->address_id);

        // 招标中状态
        $group_pass = TabTender::returnStatusGroup(TabTender::TENDER_STATUS_GROUP_PASS);
        // 已结束状态
        $group_end = TabTender::returnStatusGroup(TabTender::TENDER_STATUS_GROUP_END);

        // 获取当前时间到招标结束时间的剩余时间（单位：秒）
        $now_time = time();
        $end_time = strtotime($info->end_time);
        // 如果招标已结束，那么不管到没到结束时间，都是已结束
        if (in_array($info->status, TabTender::returnStatusGroup(TabTender::TENDER_STATUS_GROUP_END))) {
            $diff_time = 0;
        } else {
            // 如果结束时间大于当前时间，计算时间差
            if (bccomp($end_time, $now_time) == 1) {
                $diff_time = bcsub($end_time, $now_time);
            } elseif (bccomp($end_time, $now_time) == 0) {     // 如果结束时间等于（或小于）当前时间，直接为已结束
                $diff_time = 0;
            } else {
                $diff_time = 0;
            }
        }

        // 是否显示联系方式
        $can_contact = $info->can_contact == TabTender::CAN_CONTACT_YES ? 1 : 2;

        // 获取已投标店铺信息
        $bid_list = TabBid::where(['tender_id' => $info->id])->with(
            [
                'get_user_info'=>function($query){
                    $query->select('user_uuid','shop_name','store_image','company_name','contactser','contact_mobile');
                },
                'get_contact_way_info',
                'attachment_list' => function ($query) {
                    $query->where(['type' => TabAttachment::ATTACHMENT_TYPE_BID])->with('attachment_info');
                }
            ]
        )->get();
        // 投标信息-选标中状态
        $bid_status_in = TabBid::BID_STATUS_IN;
        // 投标信息-已中标状态
        $bid_status_winning = TabBid::BID_STATUS_WINNING;
        // 投标信息-未中标状态
        $bid_status_fail = TabBid::BID_STATUS_FAIL;

        SEO::setTitle($info->title . ' - 问问我建材商城');
        return view('member.tender.info', compact(
            'info',
            'group_pass',
            'group_end',
            'address_arr',
            'diff_time',
            'can_contact',
            'bid_list',
            'bid_status_in',
            'bid_status_winning',
            'bid_status_fail'
        ));
    }

    // 选标操作
    public function choice(Request $request)
    {
        $id = $request->input('id', '');
        $user_uuid = get_user_session_info('user_uuid');
        $result = TabTender::choiceTender($id, $user_uuid);
        return $result;
    }

    // 结束招标
    public function closing(Request $request)
    {
        $id = $request->input('id', '');
        $user_uuid = get_user_session_info('user_uuid');
        $update_status = TabTender::TENDER_STATUS_END_USER;
        $result = TabTender::closeTender($id, $update_status, $user_uuid);
        return $result;
    }

    public function save(Request $request)
    {
        try {
            $post = $request->except('_token');
            $result = TabTender::saveTender($post);
            return $result;
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
        }
        return  response_error('请求异常，请稍后再试');
    }

    public function getMobileCode(Request $request)
    {
        $mobile = $request->input('mobile', '');
        //验证提交的手机信息
        if (trim($mobile) === '') {
            return response_error('请输入新的手机号码', -1, ['id' => 'link_mobile']);
        }

        // 验证手机号是否符合条件
        if (!validate($mobile, 'mobile')) {
            return response_error('手机号格式错误', -1, ['id' => 'link_mobile']);
        }

        //检查验证码是否达到发送要求
        $captcha_type = DxCaptcha::SEND_TENDER_USE_NEW_MOBILE;
        $check_send_status = DxCaptcha::checkSendAgainStatus($mobile, $captcha_type);
        if ($check_send_status != 10004) {
            $return_info = DxCaptcha::checkCapReturnStatusInfo($check_send_status);
            return response_error($return_info);
        } else {
            //如果验证通过，发送短信验证码
            $status = send_messign($mobile, $captcha_type);
            //发送验证码后返回需要发送的手机号
//            $status = 1;        //测试时使用，不用浪费短信条数
            if ($status == 1) {
                return response_message('验证码已发送，请查收');
            }
            return response_error('请求异常，请点击重新发送', -1, ['id' => 'link_mobile']);
        }
    }

    // 根据状态返回该状态可操作的按钮
    public function returnBtnHtml(int $status, int $id, $data_reason = '')
    {
        $btn_html = '';
        $end_btn_html = '<a class="make_end yellow" href="javascript:;">结束招标</a>';
        $edit_btn_on_html = '<a class="yellow" href="' . to_route('member.tender.edit', ['id' => $id]) . '" target="_blank">编辑招标</a>';        // 可点击
        $edit_btn_off_html = '<a class="french_grey" href="javascript:;" target="_blank">编辑招标</a>';    // 不可点击
        $info_btn_html = '<a class="yellow" href="' . to_route('member.tender.info', ['id' => $id]) . '" target="_blank">查看详情</a>';
        $reason_btn_html = '<a class="yellow reason" data-reason="'.$data_reason.'" href="javascript:;">查看原因</a>';
        /**
         * 待审核：没有按钮
         * 招标中（无人投标）：结束招标、编辑招标、查看详情
         * 招标中（有人投标）：结束招标、编辑招标（不可点击）、查看详情
         * 已结束：编辑招标（不可点击）、查看详情
         * 未通过审：编辑招标、查看原因
         * 永久下架：编辑招标（不可点击）、查看原因
         */
        switch ($status) {
            case TabTender::TENDER_STATUS_SUCCESS:
                $btn_html .= $end_btn_html;
                $btn_html .= $edit_btn_on_html;
                $btn_html .= $info_btn_html;
                break;
            case TabTender::TENDER_STATUS_IN:
                $btn_html .= $end_btn_html;
                $btn_html .= $edit_btn_off_html;
                $btn_html .= $info_btn_html;
                break;
            case TabTender::TENDER_STATUS_END:
            case TabTender::TENDER_STATUS_END_USER:
            case TabTender::TENDER_STATUS_END_AUTO:
                $btn_html .= $edit_btn_off_html;
                $btn_html .= $info_btn_html;
                break;
            case TabTender::TENDER_STATUS_FAIL:
                $btn_html .= $edit_btn_on_html;
                $btn_html .= $reason_btn_html;
                break;
            case TabTender::TENDER_STATUS_DEL:
                $btn_html .= $edit_btn_off_html;
                $btn_html .= $reason_btn_html;
                break;
            default:
                $btn_html = '';
                break;
        }
        return $btn_html;
    }

    // 返回状态信息展示html
    public function returnStatusInfoHtml(int $status, $status_msg)
    {
        $default_html = '<span class="w120">' . $status_msg . '</span>';                 // 默认样式
        $french_grey_html = '<span class="w120 french_grey">' . $status_msg . '</span>';    // 灰色字体
        /**
         * 使用默认样式的状态有：待审核、招标中
         * 使用灰色字体的状态有：未通过审、已结束、永久下架
         */
        switch ($status) {
            case TabTender::TENDER_STATUS_PENDING:
            case TabTender::TENDER_STATUS_SUCCESS:
            case TabTender::TENDER_STATUS_IN:
                $html = $default_html;
                break;
            case TabTender::TENDER_STATUS_FAIL:
            case TabTender::TENDER_STATUS_END:
            case TabTender::TENDER_STATUS_END_USER:
            case TabTender::TENDER_STATUS_END_AUTO:
            case TabTender::TENDER_STATUS_DEL:
                $html = $french_grey_html;
                break;
            default:
                $html = '<span class="w120">未知信息</span>';
                break;
        }
        return $html;
    }
}
