<?php

namespace App\Http\Controllers\Admin;

use App\Facades\SEO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BooksRequest;
use App\Http\Requests\Admin\TemplateTypeRequest;
use App\Models\SrvOrder;
use App\Models\TabTender;
use App\Models\Template;
use App\Models\TemplateType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * 后台静态页面管理
 *
 * Class StaticsController
 * @package App\Http\Controllers\Admin
 * @author wym
 */
class StaticsController extends Controller
{

    /**
     * 静态页分类
     * 静态页分类，内容
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index (Request $request, int $typeId) {
        if (empty($typeId)) {
            return redirect()->route('admin.statics.types');
        }
        $param = $request->only('title','calls','status');
        $typeInfo = TemplateType::where('id',$typeId)->first();
        if (!$typeInfo) {
            return redirect()->route('admin.statics.types');
        }
        $data = Template::where(function ($query) use ($param) {
            if (!empty($param['title'])) {
                $query->where('title','like','%'.htmlspecialchars(trim($param['title'])).'%');
            }
            if (!empty($param['calls'])) {
                $query->where('call_key','like','%'.htmlspecialchars(trim($param['calls'])).'%');
            }
        })->where(function ($query) use ($param) {
            if (!empty($param['status'])) {
                $query->where('status',intval($param['status']));
            }
        })->where('type_id',$typeId)->orderBy('id','desc')->paginate();
        $status = TemplateType::STATUS_ARR;
        return view('admin.statics.index',compact('data','typeInfo','status','param'));
    }


    /**
     * 修改分类信息，添加
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function templateExit(int $typeId, $id = 0) {
        $typeInfo = TemplateType::where('id',$typeId)->first();
        if (!$typeInfo) {
            return redirect()->route('admin.statics.types');
        }
        $info = [];
        if (!empty($id)) {
            $info = Template::where('id', intval($id))->first();
        }
        return view('admin.statics.template_edit',compact('info','typeInfo'));
    }

    /**
     * 保存静态分类
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function templateSave (BooksRequest $request) {
        try {
            $requestData = $request->only('type_id','title','call_key','body','status');
            (new Template())->insertTemplate($requestData, $request->get('id') ?? 0);
            return response_message('编辑成功');
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
        }
        return response_error($exception->getMessage());

    }

    /**
     * 分类页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function templateType (Request $request) {
        $param = $request->only('title','calls','status');
        $data = TemplateType::where(function ($query) use ($param) {
            if (!empty($param['title'])) {
                $query->where('title','like','%'.htmlspecialchars(trim($param['title'])).'%');
            }
            if (!empty($param['calls'])) {
                $query->where('call_key','like','%'.htmlspecialchars(trim($param['calls'])).'%');
            }
        })->where(function ($query) use ($param) {
            if (!empty($param['status'])) {
                $query->where('status',intval($param['status']));
            }
        })->orderBy('id','desc')->paginate();
        $status = TemplateType::STATUS_ARR;
        return view('admin.statics.type',compact('data','param','status'));
    }

    /**
     *  编辑分类信息
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function templateTypeEdit($id = 0) {
        $info = [];
        if (!empty($id)) {
            $info = TemplateType::where('id', intval($id))->first();
        }
        return view('admin.statics.type_edit',compact('info'));
    }


    /**
     * 保存分类信息
     * @param TemplateTypeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function templateTypeSave (TemplateTypeRequest $request) {
        try {
            $requestData = $request->only('call_key','title','css_path','js_path','extend_base_path','status');
            (new TemplateType())->insertType($requestData, $request->get('id') ?? 0);
            return response_message('提交信息成功');
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
        }
        return response_error($exception->getMessage());
    }


    /**
     * 预览信息
     * @param string $callKey 调用key
     * @param int $isType 1： 内容； 2：分类下内容
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function preview (string $callKey, int $isType) {
        if (empty($callKey)) {
            return redirect()->route('admin.statics.types');
        }
        if ($isType == 1) {
            $typeId = Template::where('call_key', $callKey)->where('status',Template::STATUS_ON)->value('type_id');
            if (empty($typeId)) {
               Log::warning('未找到改类型template信息'.$callKey);
                return redirect()->route('admin.statics.types');
            }
            $result  = Template::where('type_id',$typeId)->where('status',Template::STATUS_ON)->get();
            $typeInfo = TemplateType::where('id',$typeId)->first();
        } else {
            $typeInfo = TemplateType::where('call_key', $callKey)->first();
            if (empty($typeInfo)) {
                Log::warning('未找到改类型templateType信息'.$callKey);
                return redirect()->route('admin.statics.types');
            }
            $result  = Template::where('type_id',$typeInfo->id)->where('status',Template::STATUS_ON)->get();
        }
        $tempalteName = (explode('_',strtolower($typeInfo->call_key))[1] ?? 'index');
        $array = [
            'extendBaseUrl' => $typeInfo->extend_base_path,
            'templateJs'    => $typeInfo->js_path ? explode(';', $typeInfo->js_path) : '',
            'templateCss'   => $typeInfo->css_path ? explode(';', $typeInfo->css_path) : '',
            'isType'        => $isType,
            'callKey'       => $callKey,
            'template_name' => strtoupper($tempalteName),
            'result'        => array_pluck($result,null,'call_key'),
        ];
        if ($tempalteName == 'index') {
            $array['tenderRecommend'] = (new TabTender())->recommend();
        }
        SEO::setTitle('页面预览');
        return view('admin.statics.'.$tempalteName.'_preview',$array);
    }




    /**
     * 更新单词缓存，还是更新分类缓存
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearCacheData (string $callKey, int $isType) {
        if (empty($callKey)) {
            return response_error('清楚异常！');
        }
        try {
            if ($isType == 1) { // 清理单个缓存内容
                $templateInfo = Template::where('call_key', $callKey)->where('status',Template::STATUS_ON)->first();
                if (empty($templateInfo)) {
                    return response_error('未找到内容信息！');
                }
                \Cache::forget($callKey);
                \Cache::forever($callKey,$templateInfo->body);
            } else if ($isType == 2) { // 清理分类下的缓存内容
                $typeInfo = TemplateType::where('call_key', $callKey)->where('status',TemplateType::STATUS_ON)->first();
                if(empty($typeInfo)) {
                    return response_error('未找到内容信息！');
                }
                $templateResult = Template::where('type_id', $typeInfo->id)->where('status',Template::STATUS_ON)->get();
                if (empty($templateResult)) {
                    return response_error('未找到内容信息！');
                }
                foreach ($templateResult as $k=>$v) {
                    \Cache::forget($v->call_key);
                    \Cache::forever($v->call_key,$v->body);
                }
            }
            return response_message('清理缓存成功！');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
        return response_error($exception->getMessage());
    }
}
