<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PubSeoRule;
use App\Http\Requests\Admin\SeoRuleSaveRequest;
use Exception;
use Log;

/**
 * 后台SEO配置管理
 *
 * Class SeoController
 * @package App\Http\Controllers\Admin
 * @author dch
 */
class SeoController extends Controller
{
    /**
     * 规则列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ruleList()
    {
        $title = request()->get('title') ?? '';
        return view('admin.seo.rule_list',['lists'=>PubSeoRule::where(function ($query) use ($title) {
            if ($title)  {
                $query->where('title','like','%'.htmlspecialchars(trim($title)).'%');
            }
        })->get(),'where' => ['title'=>$title]]);
    }

    /**
     * 规则添加
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ruleAdd()
    {
        return view('admin.seo.rule_edit');
    }

    /**
     * 规则修改
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ruleEdit($id)
    {
        $ruleData = PubSeoRule::find($id);

        return view('admin.seo.rule_edit',$ruleData ?? []);
    }

    /**
     * 规则保存
     * @param SeoRuleSaveRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ruleSave(SeoRuleSaveRequest $request)
    {
        try {
            $PubSeoRule = new PubSeoRule;
            $ruleData = $request->only('call_key', 'page_name', 'title', 'keywords', 'description');
            if ($id = $request->get('id')) {
                $PubSeoRule->where('id', $id)->update($ruleData);
            } else {
                $PubSeoRule->fill($ruleData)->save();
            }
        } catch (Exception $e) {
            Log::error($e);
        }
        return redirect()->to('admin/seo/rule_list');
    }

}
