<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SysSensitive;
use Illuminate\Http\Request;
use Log;

/**
 * Created by PhpStorm.
 * Class: 敏感词
 * User: wym
 */
class SensitiveController extends Controller
{

    /** 敏感词列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        try{
            $title = $request->get('title');
            $where = [];
            if ($title) {
                $where = [['name','like','%'.htmlspecialchars(trim($title)).'%']];
            }
            $data = SysSensitive::where($where)->orderBy('id','desc')->paginate(SysSensitive::PAGE_NUM);
            return view('admin.sensitive.index',['data'=>$data,'where'=>['title'=>$title],'status'=>SysSensitive::STATUS_ARR]);
        }catch (\Exception $e) {
            Log::warning($e);
        }
    }

    /**
     * 敏感词-页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request){
        $id = $request->get('id');
        $info = [];
        if (!empty($id)) {
            $info = SysSensitive::where('id',intval($id))->first();
        }
        return view('admin.sensitive.edit',['info'=>$info]);
    }


    /**
     * 敏感词-保存
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request){
        $param = $request->only('id','sensitive_name');
        try {
            if (empty(htmlspecialchars(trim($param['sensitive_name'])))) {
                return redirect()->back()->with('error','请填写敏感词内容');
            }
            if (empty(intval($param['id']))) {
                if (SysSensitive::where('name',htmlspecialchars(trim($param['sensitive_name'])))->count()) {
                    return redirect()->back()->with('error','词汇已存在，换一个吧!');
                }
                $tab = SysSensitive::insert(['name'=>htmlspecialchars(trim($param['sensitive_name']))]);
            } else {
                if (SysSensitive::where('name',htmlspecialchars(trim($param['sensitive_name'])))->where('id','<>', intval($param['id']))->count()) {
                    return redirect()->back()->with('error','词汇已存在，换一个吧!');
                }
                $tab = SysSensitive::where('id',intval($param['id']))->update(['name'=>htmlspecialchars(trim($param['sensitive_name']))]);
            }
            if (empty($tab)) {
                return redirect()->back()->with('error','敏感词信息保存失败!');
            }
            return redirect(route('admin.sensitive.index'))->withSuccess('敏感词信息保存成功!');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
        return redirect()->back()->with('error',$exception->getMessage());
    }


    /**
     * 修改敏感词状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editStatus (Request $request) {
        $param = $request->only('id','status');
        if (empty($param['id'])) {
            return response_error('请求参数错误');
        }
        if (SysSensitive::where('id',intval($param['id']))->update(['status'=>intval($param['status'])]) ) {
            return response_success('操作成功！',200);
        }
        return response_error('操作失败');
    }

    /**
     * 生成敏感词文件
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendfile() {
        set_time_limit(0);
        try {
            $sendFile = app_path().'/../public/static/mgck.text';
            $data = SysSensitive::where('status',SysSensitive::STATUS_NORMAL)->orderBy('id','desc')->pluck('name')->toArray();
            $str = '';
            array_unique($data);
            foreach ($data as  $val) {
                $str .= str_replace(PHP_EOL,'',$val).PHP_EOL;
            }
            file_put_contents($sendFile,$str);
            return redirect(route('admin.sensitive.index'))->withSuccess('敏感词文成生成成功!');
        } catch (\Exception $e) {
            Log::warning($e->getMessage());
            return redirect()->back()->with('error','生成敏感词文件出错!');
        }
    }
}
