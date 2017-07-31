<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\FriendlyLinkRequest;
use App\Models\FriendlyLink;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FriendlyLinkController extends Controller
{
    //
    /**
     * 友情链接列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lists(Request $request)
    {
        $param = $request->only('website', 'content');

        $lists = FriendlyLink::where(function ($query) use ($param)
            {
                if ($param['website']) {
                    if (is_numeric($param['website'])) {
                        $query->where('id', intval($param['website']));
                    } else {
                        $query->where('website', 'like', "%".htmlspecialchars($param['website'])."%");
                    }
                }
            })->where(function ($query) use ($param) {
                if ($param['content']) {
                    $query->where('content','like','%'.htmlspecialchars($param['content']).'%');
                }
            })->select("id",'website','weburl','content','status')->orderBy('id','desc')->paginate();
        return view('admin.friendly_link.link_lists',['lists' => $lists,'where' => $param]);
    }

    /**
     * 显示添加页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(){
        //$link = BlogRoll::where('id',$id)->first();
        return view('admin.friendly_link.link_add');
    }

    /**
     * 添加数据
     * @param FriendlyLinkRequest $request
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function append(FriendlyLinkRequest $request){
        $data = $request->only('id','website','weburl','content','email','sort');
        unset($data['_token']);
            $insertData = [
                'website' => htmlspecialchars($data['website']),
                'weburl' => $data['weburl'],
                'content' => htmlspecialchars($data['content']),
                'email' => $data['email'],
                'sort' => intval($data['sort'])
            ];
        $result = FriendlyLink::insert($insertData);
        if($result == 1){
            return response_success(['url'=>route('admin.friendly_link.lists')],'添加成功');
        }
        return json_encode(['status'=>$result['status'],'msg' => $result['msg']]);
    }

    /**
     * 显示修改页面
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id){
        $links = FriendlyLink::where('id',$id)->first();
        return view('admin.friendly_link.link_edit',['link'=>$links]);
    }
	
    /**
     * 修改
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(){
        $data = request(['id','website','weburl','content','email','sort']);
        $id = $data['id'];
        unset($data['id']);
        $result = FriendlyLink::where('id',$id)->update($data);

        if($result == 1){
            return response_message('修改成功');
        }
        return response_error('修改失败');
    }

    /**
     * 待审核和信息才显示
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function examine($id){
        $info = FriendlyLink::where(['id'=>$id,'status'=>FriendlyLink::LINK_STATUS_PENDING])->first();
        if(empty($info)){
            return error_show_msg('信息不存在或信息状态不可进行审核操作，请刷新后再试');
        }
        // 审核通过的状态
        $success_status = FriendlyLink::LINK_STATUS_SUCCESS;
        // 审核拒绝的状态
        $fail_status = FriendlyLink::LINK_STATUS_FAIL;
        // 审核删除的状态
        $del_status = FriendlyLink::LINK_STATUS_DEL;

        return view('admin.friendly_link.link_info',compact('info','success_status','fail_status','del_status'));
    }

    /**
     * 友情连接审核
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function change_status(Request $request)
    {
        // 获取提交上来的id
        $id = $request->input('id', '');
        if ($id === '') {
            return response_error('请求异常，请刷新后再试');
        }
        // 获取提交上来的状态
        $status = $request->input('status', '');
        if ($status === '') {
            return response_error('请求异常，请刷新后再试');
        }
        // 获取提交上来的原因
        $reason = $request->input('content', []);
        // 检查提交上来的状态是否符合条件
        $whereIn_arr = [FriendlyLink::LINK_STATUS_PENDING];
        switch ($status) {
            case FriendlyLink::LINK_STATUS_FAIL:
                // 如果是拒绝通过审核，需要填写拒绝原因
                if (empty($reason)) {
                    return response_error('请填写拒绝原因');
                }
                $return_info = '拒绝成功';
                break;
            case FriendlyLink::LINK_STATUS_SUCCESS:
                $return_info = '审核成功';
                break;
            default:
                return response_error('请求异常，请刷新后再试3');
        }
        // 检查id是否满足改变为提交状态的条件
        $info = FriendlyLink::where(['id'=>$id])->whereIn('status',$whereIn_arr)->first();
        if(empty($info)){
            return response_error('审核信息不存在或信息状态不可进行审核操作，请手动刷新后再试');
        }
        $save_data['status'] = $status;
        if(in_array($status,[FriendlyLink::LINK_STATUS_FAIL])){
            $save_data['reason'] = $reason;
        }
        //
        $result = FriendlyLink::where(['id'=>$id,'status'=>FriendlyLink::LINK_STATUS_PENDING])->update($save_data);
        if($result){
            return response_message($return_info);
        }
        return response_error('请求异常，请刷新后再试');
    }

    public function del(Request $request){
        $id = $request->get('id');
        if (empty($id)) return response_error('参数错误');
        $save_data['status'] = FriendlyLink::LINK_STATUS_DEL;
        $result = FriendlyLink::where(['id'=>$id])->update($save_data);

        if($result == 1){
            return response_success([],'删除成功');
        }
        return response_error('删除失败');
    }
}
