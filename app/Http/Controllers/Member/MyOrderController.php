<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Libraries\Payment\Payment;
use App\Libraries\WxpayAPI;
use App\Models\ContactWay;
use App\Models\Goods;
use App\Models\Comment;
use App\Models\SrvCommentScore;
use App\Models\SrvOrder;
use App\Models\SrvOrderGoods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 我购买的订单
 * @author  wym
 * Class MyOrderController
 * @package App\Http\Controllers\Member
 */
class MyOrderController extends Controller
{

    /**
     * 保存购买者对经销商评价
     * @param Request $request
     * content  flower_type  labels  star_work  star_quality  star_service
     *  内容     好 中 差     标签    发送速度    商品质量      服务态度
     * @param int $orderSn
     * @return \Illuminate\Http\JsonResponse
     */
    public function productComment(Request $request, int $orderSn)
    {
        try {
            $content = (string)$request->request->get('content');
            $commentType = $request->request->get('flower_type'); //只会是1,2,3
            $labels = (array)$request->request->get('labels');//印象
            $labels = array_unique(array_filter($labels));

            $commentScore = new SrvCommentScore();
            $starWork = $commentScore->star2score($request->request->get('star_work'));
            $starQuality = $commentScore->star2score($request->request->get('star_quality'));
            $starService = $commentScore->star2score($request->request->get('star_service'));

            if (!in_array($commentType, [
                Comment::COMMENT_TYPE_GOOD,
                Comment::COMMENT_TYPE_MEDIUM,
                Comment::COMMENT_TYPE_POOR
            ])
            ) {
                $commentType = Comment::COMMENT_TYPE_GOOD;
            }
            if (empty($content)) {
                return response_error('评价内容不能为空');
            }
            if (mb_strlen($content) < 5) {
                return response_error('评价内容不能少于5个字');
            }
            if (mb_strlen($content) > 500) {
                return response_error('评价文字已超出'.(mb_strlen($content) - 500).'个字');
            }
            if (empty($labels)) {
                return response_error('请选择一个印象');
            }
            $labels = implode(',', $labels); //也就是单个标签不会带逗号

            $datetime = date('Y-m-d H:i:s');
            $userUuid =  get_user_session_info('user_uuid');
            $orderInfo = SrvOrder::where([
                ['order_sn', '=', $orderSn],
                ['user_uuid', '=', $userUuid],
                ['status', '=', SrvOrder::ORDER_STATUS_OVER]
            ])->first();
            if (empty($orderInfo)) {
                return response_error('订单须在交易完成后才能进行评价');
            }
            if (in_array($orderInfo['comment_status'],[SrvOrder::ORDER_COMMENT_STATUS_ALL,SrvOrder::ORDER_COMMENT_STATUS_EMPLOYER])) {
                return response_error('您已评价,请勿重复评价');
            }
            DB::beginTransaction();
            $updateCommentRet = SrvOrder::where([
                ['order_sn', '=', $orderSn],
                ['user_uuid', '=', $userUuid],
                ['status', '=', SrvOrder::ORDER_STATUS_OVER],
                ['comment_status', '=', $orderInfo['comment_status']]
            ])->update([
                'comment_status' => ($orderInfo['comment_status'] == SrvOrder::ORDER_COMMENT_STATUS_SERVER ? SrvOrder::ORDER_COMMENT_STATUS_ALL : SrvOrder::ORDER_COMMENT_STATUS_EMPLOYER)
            ]);
            if (!$updateCommentRet) {
                DB::rollBack();
                return response_error('操作异常,请稍后刷新再试');
            }
            // 对订单商品修改评价状态
            SrvOrderGoods::where([['order_sn','=',intval($orderSn)],['user_uuid','=',$userUuid]])->update(['comment_status'=>1]);

            // 对商品信息进行评价
            $orderGoodsData = SrvOrderGoods::where([['order_sn', '=', intval($orderSn)],['user_uuid','=',$userUuid]])->select('goods_id','sku_id','goods_title','amount','sku_extend')->get()->toArray();
            if (empty($orderGoodsData)) {
                DB::rollBack();
                return response_error('操作异常,请稍后刷新再试');
            }

            $dealerUserUuid = DB::table('firm_and_dealer')->where('id', $orderInfo['shop_id'])->value('user_uuid');

            if (empty($dealerUserUuid)) {
                DB::rollBack();
                return response_error('操作异常,请稍后刷新再试');
            }

            $newComment = []; // 评价表
            $batchScore = []; // 打分表
            $goodsIdArr = []; // 需要自增+1的评价数的商品
            foreach ($orderGoodsData as $k=>$v) {
                $newComment [] = [
                    'order_id'     => $orderSn,
                    'from_id'      => $userUuid,
                    'to_id'        => $dealerUserUuid,
                    'create_at'    => $datetime,
                    'content'      => $content,
                    'comment_type' => $commentType,
                    'labels'       => $labels,
                    'object_id'    => $v['sku_id'],
                    'title'        => $v['goods_title'],
                    'finish_price' => $v['amount'],
                    'from_type'    => Comment::FROM_TYPE_USER,
                    'object_type'  => Comment::OBJECT_TYPE_PRODUCT,
                    'goods_extend' => $v['sku_extend'],
                    'goods_id'     => $v['goods_id']
                ];
                $batchScore[] = [
                    'order_id'   => $orderSn,
                    'from_id'    => $userUuid,
                    'to_id'      => $dealerUserUuid,
                    'from_type'  => Comment::FROM_TYPE_USER,
                    'create_at'  => $datetime,
                    'score_type' => SrvCommentScore::SCORE_TYPE_WORK,
                    'score'      => $starWork,
                    'sku_id'     => $v['sku_id'],
                    'goods_id'   => $v['goods_id']
                ];
                $batchScore[] = [
                    'order_id'   => $orderSn,
                    'from_id'    => $userUuid,
                    'to_id'      => $dealerUserUuid,
                    'from_type'  => Comment::FROM_TYPE_USER,
                    'create_at'  => $datetime,
                    'score_type' => SrvCommentScore::SCORE_TYPE_QUALITY,
                    'score'      => $starQuality,
                    'sku_id'     => $v['sku_id'],
                    'goods_id'   => $v['goods_id']
                ];
                $batchScore[] = [
                    'order_id'   => $orderSn,
                    'from_id'    => $userUuid,
                    'to_id'      => $dealerUserUuid,
                    'from_type'  => Comment::FROM_TYPE_USER,
                    'create_at'  => $datetime,
                    'score_type' => SrvCommentScore::SCORE_TYPE_SERVICE,
                    'score'      => $starService,
                    'sku_id'     => $v['sku_id'],
                    'goods_id'   => $v['goods_id']
                ];
                $goodsIdArr[] = $v['goods_id'];
            }

            if(!Comment::insert($newComment)){
                DB::rollBack();
                return response_error('评价系统异常,请稍后刷新再试');
            }
            if (!SrvCommentScore::insert($batchScore)) {
                DB::rollBack();
                return response_error('评价系统异常,请稍后刷新再试');
            }
            (new Goods())->setGoodsFiledNum($goodsIdArr);

            DB::commit();
            return response_message('评价成功');
        } catch (\Exception $e) {
            \Log::warning($e);
        } catch (\Throwable $e) {
            \Log::warning($e);
        }
        return response_error('评价系统异常,请稍后刷新再试');
    }

    public function lists(Request $request)
    {
        \SEO::setTitle('我的订单');
        $where = $request->all();
        $where['s'] = $where['s'] ?? SrvOrder::ORDER_PAY_STATUS_WAITING;
        $lists = SrvOrder::where('user_uuid',get_user_session_info('user_uuid'))->where('order_parent_sn','!=','0')->where(function($query) use($where){
            $query->where('status',intval($where['s'] ?? SrvOrder::ORDER_PAY_STATUS_WAITING));
        })->where(function($query) use($where){
            if (($where['time_type'] ?? 0)> 0){
                $query->whereBetween('created_at',[$where['begin_time'],$where['end_time']]);
            }
        })->where(function($query) use($where){
            if (!empty($where['sn'])){
                $query->where('order_sn','like','%'.htmlspecialchars($where['sn']).'%');
            }
        })->where(function ($query) use ($where) {
            if (isset($where['comment'])) {
                switch ($where['comment']) {
                    case 1:
                        $query->whereIn('comment_status',[SrvOrder::ORDER_COMMENT_STATUS_EMPLOYER,SrvOrder::ORDER_COMMENT_STATUS_ALL]);
                        break;
                    default:
                        $query->whereNotIn('comment_status',[SrvOrder::ORDER_COMMENT_STATUS_EMPLOYER,SrvOrder::ORDER_COMMENT_STATUS_ALL]);
                        break;
                }
            }
        })->orderBy('created_at','DESC')->paginate(6);
        $data = [];
        $overOrderCommentId = [];
        foreach($lists as $key=>$value){
            $overOrderCommentId[] = ($value->status == SrvOrder::ORDER_STATUS_OVER) ? (!in_array($value->comment_status, [SrvOrder::ORDER_COMMENT_STATUS_EMPLOYER,SrvOrder::ORDER_COMMENT_STATUS_ALL]) ? $value->order_sn : [])  : [];
            $data[] = SrvOrder::getOrderInfo($value->order_sn);
        }
        $dataItem = $lists->toArray();

        $array = [
            SrvOrder::ORDER_PAY_STATUS_WAITING => '待付款',
            SrvOrder::ORDER_LOGISTICS_STATUS_SHIP => '待发货',
            SrvOrder::ORDER_LOGISTICS_STATUS_RECEIPT => '待确认收货',
            SrvOrder::ORDER_STATUS_OVER => '交易成功',
            SrvOrder::ORDER_STATUS_CANCEL=>'交易关闭',
        ];

        // 状态下的数量
        $statusTotalSqlArr = [];
        $statusTotalSql = 'count(case when status = %d then 1 else NULL end) as %s';
        foreach ($array as $key=>$value){
            $statusTotalSqlArr[] = DB::raw(sprintf($statusTotalSql, $key, 'status'.$key));
        }
        $condition = SrvOrder::select($statusTotalSqlArr)->where('user_uuid',get_user_session_info('user_uuid'))->where('order_parent_sn','!=','0')->orderBy('created_at','DESC')->first()->toArray();

        // 评价
        $commentTotalSql = "count(case when (comment_status = %d or comment_status = %d)  then 1 else NULL end) as %s";
        $comment = SrvOrder::select(
            [
                \DB::raw(sprintf($commentTotalSql, SrvOrder::ORDER_COMMENT_STATUS_EMPLOYER, SrvOrder::ORDER_COMMENT_STATUS_ALL, 'yes_count')),
                \DB::raw(sprintf($commentTotalSql, SrvOrder::ORDER_COMMENT_STATUS_WAITING, SrvOrder::ORDER_COMMENT_STATUS_SERVER, 'no_count')),
            ]
        )->where('status',SrvOrder::ORDER_STATUS_OVER)->where('user_uuid', get_user_session_info('user_uuid'))->first()->toArray();

        return view('member.order.lists',compact('data','lists','dataItem','where','condition','array','comment','overOrderCommentId'));
    }

    /**
     * 获取订单详情
     * @author: liufangyuan
     * @date: 2016-12-8
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request)
    {
        $orderSn = (int)$request->get('order_sn');
        \SEO::setTitle('订单详情');
        if (empty($orderSn)){
            return redirect()->to(to_route('member.order.lists'));
        }

        //验证是否订单信息是否存在
        $info = SrvOrder::getOrderInfo($orderSn);
        if (!$info){
            return redirect()->to(to_route('member.order.lists'));
        }
        //获取联系方式
        $contactsInfo = ContactWay::where('shop_id',$info->shop_id)->first();
        return view('member.order.show',compact('info','contactsInfo'));

    }

    /**
     * 取消订单
     * @author: liufangyuan
     * @date: 2016-12-8
     * @param Request $request
     * @return string
     */
    public function cancelOrder(Request $request)
    {
        $orderSn = (int)$request->get('order_sn');
        $result = SrvOrder::cancelOrder($orderSn);
        return json_encode(['status'=>$result['status'],'msg'=>$result['msg']]);
    }


    /**
     * 商品确认收货
     * @author: liufangyuan
     * @date: 2016-12-8
     * @param Request $request
     * @return string
     */
    public function confirmReceipt(Request $request)
    { 
        try {
            $orderSn = (int)$request->get('order_sn');
            $result = SrvOrder::confirmReceipt($orderSn);
            return json_encode(['status'=>$result['status'],'msg'=>$result['msg']]);
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
        }
        return response_error('请求异常');
    }

    /**
     * 支付页面
     * @author: liufangyuan
     * @date: 2016-12-8
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pay(Request $request)
    {
        \SEO::setTitle('订单支付');
        //获取订单
        $orderSn = (int)$request->get('order_sn');
        if (empty($orderSn)){
            return redirect()->to(to_route('dealer.shopping.settlement'));
        }
        $result = SrvOrder::getOrderInfo($orderSn);

        if (!$result){
            return redirect()->to(to_route('dealer.shopping.settlement'));
        }

        if ($result->status != SrvOrder::ORDER_PAY_STATUS_WAITING){
            return redirect()->to(to_route('dealer.order.lists'));//我的采购订单
        }

        $title = '订单支付';
        return view('member.order.pay',compact('result','title'));
    }

    /**
     *同步回调地址
     * @author: liufangyuan
     * @date: 2016-12-8
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function returnUrl(Request $request)
    {
        $order_sn = (int)$request->get('orderId');
        if ($this->isSuccessfulAndHandle($request)) {
            return redirect()->to(to_route('member.order.lists'));
        } else {
            return redirect()->to(to_route('member.pay',['order_sn'=>$order_sn]));
        }
    }

    protected function isSuccessfulAndHandle(Request $request)
    {

        try {
            DB::beginTransaction();
            $params = $request->request->all();
            $payment = new Payment();
            if ($payment->factory('UnionPay')->setParameters($params)->isSuccessful()) {
                //交易成功,处理业务逻辑
                Log::info(sprintf("交易返回信息:%s", var_export($params, true)));
                if (SrvOrder::updateStatus($params)) {
                    DB::commit();
                    return true;
                }
            }
            DB::rollBack();
            return false;
        } catch (\Exception $e) {
            Log::warning($e);
        } catch (\Throwable $e) {
            Log::warning($e);
        }
        return false;
    }

    /**
     * 发送post支付请求
     * @author: liufangyuan
     * @date: 2016-12-8
     * @param Request $request
     * @param SrvOrder $srvOrder
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postPay(Request $request,SrvOrder $srvOrder){
        $orderSn = (int)$request->get('order_sn');

        //验证订单是否存在
        if (empty($orderSn)){
            if ($request->ajax()){
                return response_error('参数错误',-4);
            }
            return redirect()->to(to_route('member.order.lists'));
        }
        $orderInfo =$srvOrder::getOrderInfo($orderSn);
        if (!$orderInfo){
            if ($request->ajax()){
                return response_error('参数错误',-4);
            }
            return redirect()->to(to_route('member.order.lists'));
        }

        if($orderInfo->status != SrvOrder::ORDER_PAY_STATUS_WAITING){
            if ($request->ajax()){
                return response_error('参数错误',-4);
            }
            return redirect()->to(to_route('member.order.lists'));
        }
        $type = $request->get('type',2);
        if (!in_array($type,[1,2,3])){
            if ($request->ajax()){
                return response_error('参数错误',-4);
            }
            return redirect()->to(to_route('member.order.lists'));
        }
        try{
            switch ($type){
                case 1://微信支付
                    $result = (new WxpayAPI\WechatPay())->getCodeUrl('问问我建材商城购买商品',$orderInfo->order_sn,$orderInfo->total_price*100,to_route('dealer.pay.wx_notify_url'));
                    if ($result){
                        return response_success(['html'=>\QrCode::size(235)->generate($result)]);
                    }else {
                        return response_error('发起支付失败，请稍后再试');
                    }
                    break;
                case 2://银联支付
                    $payment = new Payment();
                    //更新发起支付时间
                    $date = date('YmdHis', time());
                    SrvOrder::where('order_sn',$orderInfo->order_sn)->update(['txnTime'=>$date]);
                    echo $payment->factory('UnionPay')->setParameters([
                        'frontUrl' => to_route('member.pay.return_url'),  //前台通知地址
                        'backUrl'  => to_route('dealer.pay.notify_url'),   //异步通知地址
                        'orderId'  => $orderInfo->order_sn,   //商户订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数，可以自行定制规则
                        'txnTime'  => date('YmdHis', time()),    //订单发送时间，格式为YmdHis，取北京时间
                        'txnAmt'   => number_format($orderInfo->total_price * 100, 0, '.', ''),
                    ])->generateFormHtml();
                    die;
                    break;
                case 3://支付宝支付
                    break;
                default://默认银联支付
                    break;
            }
        }catch (\Exception $e){
            Log::error($e);
        }
    }

    /**
     * 验证支付状态 微信
     * @param Request $request
     */
    public function verifyPayStatus(Request $request)
    {
        $orderSn = (int)$request->get('order_sn');
        if (empty($orderSn)){
            return response_error('参数错误');
        }
        //验证订单是否存在
        $orderInfo = SrvOrder::getOrderInfo($orderSn);
        if(!$orderInfo){
            return response_error('订单信息不存在');
        }

        //验证订单是否支付成功
        $result = (new WxpayAPI\WechatPay())->orderQuery($orderSn);
        if ($result['trade_state'] == 'NOTPAY'){
            return response_error('success',-3);
        }elseif ($result['trade_state'] == 'SUCCESS') {
            return response_error('success',1);
        }elseif($result['trade_state'] == 'PAYERROR'){
            return response_error('success',-1);
        }
    }
}
