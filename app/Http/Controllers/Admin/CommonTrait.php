<?php
/**
 * @author  liufangyuan
 * Date: 2017/2/21
 * Time: 15:32
 * Created by PhpStorm.
 * User: liufangyuan
 * Date: 2017/2/21
 * Time: 15:32
 */
namespace App\Http\Controllers\Admin;
use App\Models\Goods;
use App\Models\GoodsSale;
use App\Models\SmsLog;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait CommonTrait{

    protected $modelName = self::MODEL_NAME;

    /**
     *删除数据
     * @author: liufangyuan
     * @date: 2016-12-8
     */
    public function delete(Request $request){
        if ($this->modelName == '') return response_error('请配置模型');

        $id = $request->get('id');
        if (empty($id)) return response_error('参数错误');
        //验证数据信息是否存在
        $checkInfo = DB::table($this->modelName) -> where('id','=',$id) -> first();
        if ($checkInfo){
            if ($checkInfo->status == 99) return response_error('该信息不支持删除');
        }else{
            return response_error('请求的数据信息不存在');
        }
        if (empty($request->get('reject_reason'))) {
            //改变数据状态为99（删除状态值）
            $result = DB::table($this->modelName) -> where('id','=',$id) ->update(['status'=>99]);
        } else {
            // 违规删除，也需要记录删除原因
            $result = DB::table($this->modelName)->where('id',$id)->update(['status' => Goods::GOODS_STATUS_DEL,'reject_reason' => $request->get('reject_reason')]);
            switch ($this->modelName) {
                case 'goods' :
                    $content = get_sms_template('GOODS_STATUS_DEL', ['goods_title' => $checkInfo->title]);
                    if (!empty($content) && $checkInfo->user_uuid) {
                        self::sendMailContent(['content' => $content, 'user_uuid' => $checkInfo->user_uuid, 'title' => '违规删除']);
                    }
                    break;
            }
        }
        if($result){
            $logArray = [
                'ip' => get_client_ip(),
                'worker'=>get_admin_session_info('name'),
                'worker_id' => get_admin_session_info('id'),
                'id' => $id,
                'action' => '删除数据（'.$request->get('reject_reason').'）'
            ];
            admin_log($this->modelName,$logArray);
            return response_success([],'删除数据成功');
        }else {
            return response_error('系统繁忙，请稍后再进行删除');
        }
    }

    /**
     *设置数据状态
     * @author: liufangyuan
     * @date: 2016-12-8
     */
    public function setStatus(Request $request){
        if ($this->modelName == '') return response_error('请配置模型');
        $id = $request->get('id');
        if (empty($id)) return response_error('参数错误');
        //验证数据信息是否存在
        $checkInfo = DB::table($this->modelName) -> where('id','=',$id) -> first();
        if($checkInfo) {
            if (!in_array($checkInfo->status,[1,2])) {
                return response_error('当前请求数据不允许启用或禁用');
            }else {
                switch ($checkInfo->status){
                    case 1://当前数据是启用状态，设置为禁用
                        $result = DB::table($this->modelName) -> where('id','=',$id) -> update(['status' => 2]);
                        $action = '禁用';
                        break;
                    case 2://当前数据是禁用状态，设置为启用
                        if ($this->modelName == 'goods') {
                            $goodsSaleCount = GoodsSale::where('goods_id', $id)->where('status', GoodsSale::GOODS_STATUS_ON)->count();
                            if (empty($goodsSaleCount)) {
                                return response_error('请先编辑产品的套餐信息，才能上架');
                            } else {
                                $result = DB::table($this->modelName) -> where('id','=',$id) -> update(['status' => 1]);
                            }
                        } else {
                            $result = DB::table($this->modelName) -> where('id','=',$id) -> update(['status' => 1]);
                        }
                        $action = '启用';
                        break;
                }
                if ($result){
                    $logArray = [
                        'ip' => get_client_ip(),
                        'worker'=>get_admin_session_info('name'),
                        'worker_id' => get_admin_session_info('id'),
                        'id' => $id,
                        'action' => $action
                    ];
                    admin_log($this->modelName,$logArray);
                    return response_success([],'操作成功');
                }else {
                    return response_error('操作失败');
                }
            }
        }else {
            return response_error('请求的数据信息不存在');
        }
    }

    /**
     * 审核拒绝
     * @author: liufangyuan
     * @date: 2016-12-8
     */
    public function refuseVerify(Request $request){
        if ($this->modelName == '') return response_error('请配置模型');
        $id = $request->get('id');
        $rejectReason = $request->get('reject_reason');
        if (empty($rejectReason)) {
            return response_error('请填写拒绝原因');
        }
        if (empty($id)) return response_error('参数错误');
        //验证数据信息是否存在
        $checkInfo = DB::table($this->modelName) -> where('id','=',$id) -> first();
        if ($checkInfo){
            if ($checkInfo->status != 3) return response_error('当前请求数据不允许拒绝审核操作');

            //设置数据状态为拒绝审核
            $result = DB::table($this->modelName) -> where('id','=',$id) -> update(['status' => 4,'reject_reason' => $rejectReason]);
            if ($result) {
                $logArray = [
                    'ip' => get_client_ip(),
                    'worker'=>get_admin_session_info('name'),
                    'worker_id' => get_admin_session_info('id'),
                    'id' => $id,
                    'action' => '拒绝审核（'.$rejectReason.'）',
                ];
                admin_log($this->modelName,$logArray);

                $mailArray['user_uuid'] = $checkInfo->user_uuid ?? '';
                switch ($this->modelName) {
                    case 'goods' :
                        $mailArray['title'] = '商品被下架';
                        $content = get_sms_template('GOODS_TENDER_NO', ['goods_title'=>$checkInfo->title]);
                        $mailArray['content'] = $content;
                        break;
                    default:
                        $mailArray['title'] = trim(self::SEND_TITLE.'审核结果');
                        $mailArray['content'] = '您填写的联系方式：不通过（原因：'.$rejectReason.'）';
                        break;
                }
                if (!empty($mailArray['user_uuid'])) {
                    self::sendMailContent($mailArray);
                }
                return response_success([],'拒绝审核成功');
            }else {
                return response_error('系统繁忙，请稍后再试');
            }
        }else {
            return response_error('请求的数据信息不存在');
        }
    }

    /**
     * 审核通过
     * @author: liufangyuan
     * @date: 2016-12-8
     */
    public function passVerify(Request $request){
        if($this->modelName == '') return response_error('请配置模型');
        $id = $request->get('id');
        if (empty($id)) return response_error('参数错误');
        //验证数据信息是否存在

        $checkInfo = DB::table($this->modelName) -> where('id','=',$id) -> first();
        if ($checkInfo){
            if ($checkInfo->status != 3) return response_error('当前请求数据不允许通过审核操作');

            //设置数据状态为拒绝审核
            $result = DB::table($this->modelName) -> where('id','=',$id) -> update(['status' => 1]);
            if ($result) {
                $logArray = [
                    'ip' => get_client_ip(),
                    'worker'=>get_admin_session_info('name'),
                    'worker_id' => get_admin_session_info('id'),
                    'id' => $id,
                    'action' => '审核通过'
                ];
                admin_log($this->modelName,$logArray);
                return response_success([],'通过审核成功');
            }else {
                return response_error('系统繁忙，请稍后再试');
            }
        }else {
            return response_error('请求的数据信息不存在');
        }
    }


    /**
     * 发送消息信息
     * @param array $array user_uuid  title content
     * @return array
     */
    public function sendMailContent ($array = []) {
        if (empty($array)) {
            return [];
        }
        if (!empty($array['content'])) {
            SmsLog::newMailNotify(get_admin_session_info('id') ?? '0', SmsLog::SEND_MSM_GROUP_SPECIFIED, [SmsLog::SEND_MSM_TO_SPECIFIED_USER], ['user_uuid' => $array['user_uuid']], $array['title'], $array['content'], SmsLog::SEND_TYPE_FOR_MAIL, 1);
        }
    }
}
?>

