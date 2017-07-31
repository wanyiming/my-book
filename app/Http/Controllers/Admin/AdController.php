<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AdPositionSaveRequest;
use App\Http\Requests\Admin\AdSaveRequest;
use App\Models\Ad;
use App\Models\AdPosition;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LogicException;
use Log;
use Throwable;
use Exception;

/**
 * 广告位,广告管理
 *
 * Class AdController
 * @package App\Http\Controllers\Admin
 * @author dch
 */
class AdController extends Controller
{
    const PAGE_NUM = 25;//每页数量

    /**
     * 广告位 - 列表
     * @param AdPosition $adPosition
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function positionList(AdPosition $adPosition,Request $request)
    {
        $query = $request->only('station','sub_channel','position_name','display_mode','status');

        $condition = [];
        foreach (array_filter($query) as $filed => $value){
            $condition[] = [
                $filed,
                $filed == 'position_name' ? 'like' : '=',
                $filed == 'position_name' ? "%{$value}%" : $value
            ];
        }
        $positions = AdPosition::where(
            $condition
        )->orderBy('id','desc')->paginate(self::PAGE_NUM);
        $subChannels = $adPosition->getSubChannel();
        $displayModes = $adPosition->getDisplayMode();

        foreach ($positions as $position) {
            $position['sub_channel_cn'] = array_get($subChannels, $position['sub_channel']);
            $position['display_mode_cn'] = array_get($displayModes, $position['display_mode']);
        }

        return view('admin.ad.position_list', [
            'lists'        => $positions,
            'status'       => AdPosition::STATUS_ARR,
            'subChannels'  => $subChannels,
            'displayModes' => $displayModes
        ]);
    }

    /**
     * 广告位 - 添加
     * @param AdPosition $adPosition
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function positionAdd(AdPosition $adPosition)
    {
        $displayModes = $adPosition->getDisplayMode();
        $subChannels = $adPosition->getSubChannel();

        return view('admin.ad.position_edit', [
            'displayModes' => $displayModes,
            'subChannels'  => $subChannels,
        ]);
    }

    /**
     * 广告位 - 编辑
     * @param int $positionId
     * @param AdPosition $adPosition
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function positionEdit(int $positionId, AdPosition $adPosition)
    {
        $position = AdPosition::where('id', $positionId)->first();
        if (empty($position)) {
            return redirect()->back();
        }
        $position = array_merge($position->toArray(), json_decode($position['option'], true));

        $displayModes = $adPosition->getDisplayMode();
        $subChannels = $adPosition->getSubChannel();

        return view('admin.ad.position_edit', array_merge([
            'displayModes' => $displayModes,
            'subChannels'  => $subChannels,
        ], $position));
    }

    /**
     * 广告位 - 保存
     * @param AdPositionSaveRequest $request
     * @param AdPosition $adPosition
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function positionSave(AdPositionSaveRequest $request, AdPosition $adPosition)
    {
        try {
            $speed = $request->get('speed', 500); //默认500ms
            $position = $request->only('call_key', 'sub_channel', 'position_name', 'display_mode', 'width', 'height',
                'status');
            $position['status'] = ($position['status'] == AdPosition::STATUS_ENABLE) ? AdPosition::STATUS_ENABLE : AdPosition::STATUS_DISABLE;
            $position['call_key'] = strtoupper($position['call_key']);
            if (!array_key_exists($position['sub_channel'], $adPosition->getSubChannel())) {
                //子频道不存在
                return $this->buildFailedValidationResponse($request, ['sub_channel' => ['请选择子频道']]);
            }

            $position['option'] = json_encode(['speed' => intval($speed)]);

            AdPosition::updateOrInsert(['call_key' => $position['call_key']],
                $position);
        } catch (Exception $e) {
            Log::warning($e);
        } catch (Throwable $e) {
            Log::warning($e);
        }
        return redirect()->to('admin/ad/position_list');
    }

    /**
     * 广告位 - 修改状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adStatusEdit(Request $request)
    {
        try {
            $param = $request->only('id', 'status');
            if (empty($param['id'])) {
                return response_error('请求参数错误');
            }
            if (AdPosition::where('id', intval($param['id']))->update(['status' => intval($param['status'])])) {
                return response_success('操作成功！', 200);
            }

            return response_error('操作失败');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());

            return response_error('操作失败');
        }
    }

    /**
     * 广告 - 列表
     * @param int $positionId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function positionDetail(int $positionId)
    {
        if (!($adPosition = AdPosition::where('id', $positionId)->first())) {
            return redirect()->back();
        }
        $ads = Ad::where('position_id', $positionId)->orderBy('weight', 'asc')->get();

        return view('admin.ad.position_detail',
            ['lists' => $ads, 'positionId' => $adPosition['id'], 'status' => AdPosition::STATUS_ARR]);
    }



    /**
     * 广告 - 保存
     * @param AdSaveRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function adSave(AdSaveRequest $request)
    {
        try {
            $adId = intval($request->get('id'));
            $ad = $request->only("picture_url", "ad_name", "ad_link", "begin_time", "end_time", "weight", "position_id",
                'status');
            $ad["weight"] = intval($ad["weight"]);
            $ad["status"] = intval($ad["status"]);
            $ad["position_id"] = intval($ad["position_id"]);
            $ad["remark"] = '';

            if (!AdPosition::where('id', $ad['position_id'])->count()) {
                Log::info(sprintf('未知广告位参数:%s', print_r($ad, true)));

                return $this->buildFailedValidationResponse($request, ['weight' => ['未知广告位']]);
            }
            if ($adId) {
                Ad::where('id', $adId)->update($ad);
            } else {
                $ad["create_at"] = date('Y-m-d H:i:s');
                $ad["admin_id"] = $this->adminId();
                Ad::insert($ad);
            }
        } catch (Exception $e) {
            Log::warning($e);
        } catch (Throwable $e) {
            Log::warning($e);
        }

        return response(['error' => 0]);
    }


    protected function adminId()
    {
        if (!$adminId = get_admin_session_info('id')) {
            throw new LogicException('无法获取 adminId ');
        }

        return $adminId;
    }
}
