<?php

namespace App\Http\Controllers\Admin;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * 评论管理
 *
 * Class CommentController
 * @package App\Http\Controllers\Admin
 * @author wym
 */
class CommentController extends Controller
{

    /**
     * @param Request $request
     * 需求通过的信息
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index (Request $request) {
        $param = $request->only('status', 'begin_time', 'end_time');
        $result = [
            'data' => (new Comment())->commentLists($param),
            'status'=>Comment::STATUS_STY,
            'where'=>$param
        ];
        return view('admin.comment.review_yes_index', $result);
    }

    /**
     * 修改信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit (Request $request) {
        try {
            $id = $request->get('ids');
            $status = $request->get('state');
            if (empty($id) || empty($status)) {
                return response_error('请求参数错误');
            }
            if (SrvComment::where('id', intval($id))->update(['status'=>intval($status)]) ) {
                return response_message('操作成功');
            } else {
                return response_error('操作失败');
            }
        } catch (\Exception $e) {
            \Log::warning($e);
            return response_error('操作失败');
        }
    }
}
