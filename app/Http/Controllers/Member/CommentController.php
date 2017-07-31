<?php

namespace App\Http\Controllers\Member;

use App\Facades\SEO;
use App\Models\DisFile;
use App\Models\Goods;
use App\Models\GoodsSale;
use App\Models\Comment;
use App\Models\SrvCommentScore;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Symfony\Component\Console\Exception\LogicException;
use Throwable;
use Log;
use DB;

class CommentController extends Controller
{
    const PAGE_NUM = 7;

    /**
     * 评价列表数据
     *
     * @author wym
     * @param Request $request
     * @param Comment $srvComment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lists(Request $request, Comment $srvComment)
    {
        $commentType = (int)$request->get('comment_type');
        $condition = [];
        $queryParams = [];
        $userUUID = $this->userId();

        if ($request->get('from_type') == Comment::FROM_TYPE_DEALER) {
            $condition[] = ['to_id', '=', $userUUID];
            $queryParams['from_type'] = Comment::FROM_TYPE_DEALER;
        } else {
            $condition[] = ['from_id', '=', $userUUID];
            $queryParams['from_type'] = Comment::FROM_TYPE_USER;
        }

        if (!empty($commentType)) {
            $condition[] = ['comment_type', '=', $srvComment->filterCommentType($commentType)];
            $queryParams['comment_type'] = $srvComment->filterCommentType($commentType);
        }

        $lists = $srvComment->where($condition)->orderBy('create_at','desc')->paginate(self::PAGE_NUM);
        $lists->appends($queryParams);
        $this->formatComments($lists);
        return view('member.comment.list', [
            'lists' => $lists
        ]);
    }

    /**
     * @param \Illuminate\Pagination\LengthAwarePaginator $comments
     * @author dch
     */
    public function formatComments($comments)
    {
        if(empty($comments) || $comments->isEmpty()){
            return;
        }

        $dealerIdArr = [];
        $goodsIdArr  = [];
        foreach ($comments as $key=>$value) {
            if ($value->from_type == Comment::FROM_TYPE_USER) {
                $dealerIdArr[] = $value->to_id;
            } else {
                $dealerIdArr[] = $value->from_id;
            }
            $goodsIdArr[] = $value->goods_id;
        }
        $dealerData = [];
        if (!empty($dealerIdArr)) {
            $dealerData = \DB::table('firm_and_dealer')->whereIn('user_uuid', array_unique(array_filter($dealerIdArr)))
                ->where('seller_type',User::DEALER)->select('store_image as avatar','shop_name','user_uuid')->get()->toArray();
        }
        // 商品信息
        $goodsData = [];
        $goodsFileData = [];
        if(!empty($goodsIdArr)) {
            $goodsData = Goods::whereIn('id', array_unique(array_filter($goodsIdArr)))->pluck('cover_file_id', 'id')->toArray();
            // 得到首图地址
            $goodsFileData = (new DisFile())->fileIds2url(array_unique(array_values($goodsData)))->toArray();
        }
        $dealerData = array_pluck($dealerData, null, 'user_uuid');
        foreach ($comments as $comment) {
            if($comment->from_type == Comment::FROM_TYPE_USER){
                $comment->shop_name   = $dealerData[$comment->to_id]->shop_name ?? '';
            }else{
                $comment->shop_name   = $dealerData[$comment->from_id]->shop_name ?? '';
            }
            $comment->goods_images = isset($goodsData[$comment->goods_id]) ? ($goodsFileData[$goodsData[$comment->goods_id]] ?? '#') : '#';
            $comment->goods_extend = json_decode($comment->goods_extend,true);
        }
    }

    /**
     * 评价管理页面
     * @author wym
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $userUUID = $this->userId();
            $argTpl = 'avg(case when `score_type` = %d then `score` else NULL end) as %s';
            $totalTpl = 'count(case when `score_type` = %d then 1 else NULL end) as %s';
            $summary = SrvCommentScore::select([
                DB::raw(sprintf($argTpl, SrvCommentScore::SCORE_TYPE_TIMELINESS, 'avg_timeliness')),
                DB::raw(sprintf($argTpl, SrvCommentScore::SCORE_TYPE_HAPPY, 'avg_happy')),
                DB::raw(sprintf($totalTpl, SrvCommentScore::SCORE_TYPE_TIMELINESS, 'total_timeliness')),
                DB::raw(sprintf($totalTpl, SrvCommentScore::SCORE_TYPE_HAPPY, 'total_happy')),
            ])->where([
                ['to_id', '=', $userUUID],
                ['from_type', '=', SrvCommentScore::FROM_TYPE_SHOP]
            ])->first();
            if (empty($summary['total_timeliness'])) {
                $summary['avg_timeliness'] = $summary->defaultStar();
            }
            if (empty($summary['total_happy'])) {
                $summary['avg_happy'] = $summary->defaultStar();
            }

            $summary['avg_timeliness'] = sprintf('%.2f', $summary['avg_timeliness']);
            $summary['avg_happy'] = sprintf('%.2f', $summary['avg_happy']);
            $totalInfo = $this->totalComment($userUUID);

            if (!empty($totalInfo['total_all'])) {
                $totalInfo['good_proportion'] = bcmul(bcdiv($totalInfo['total_good'], $totalInfo['total_all'], 4), 100, 2);
            }
            $totalInfo['good_proportion'] = sprintf('%.2f', $totalInfo['good_proportion']);

            SEO::setTitle('评价管理 - 会员中心');
            return view('member.comment.index', [
                'summary'   => $summary,
                'totalInfo' => $totalInfo
            ]);
        } catch (Exception $e) {
            Log::warning($e);
        } catch (Throwable $e) {
            Log::warning($e);
        }
        return response('/');
    }

    protected function totalComment($userId)
    {
        $byTime = "count(case when create_at > '%s' then 1 else NULL end) as %s";
        $byTypeTime = "count(case when `comment_type` = %d AND create_at > '%s' then 1 else NULL end) as %s";
        $byType = "count(case when `comment_type` = %d then 1 else NULL end) as %s";

        $byTimeLt = "count(case when create_at < '%s' then 1 else NULL end) as %s";
        $byTypeTimeLt = "count(case when `comment_type` = %d AND create_at < '%s' then 1 else NULL end) as %s";
        return Comment::select([
            DB::raw('count(*) as total_all'),
            DB::raw(sprintf($byType, Comment::COMMENT_TYPE_GOOD, 'total_good')),
            DB::raw(sprintf($byType, Comment::COMMENT_TYPE_MEDIUM, 'total_medium')),
            DB::raw(sprintf($byType, Comment::COMMENT_TYPE_POOR, 'total_poor')),

            DB::raw(sprintf($byTime, date('Y-m-d H:i:s', strtotime('-1 week')), 'one_week_all')),
            DB::raw(sprintf($byTypeTime, Comment::COMMENT_TYPE_GOOD, date('Y-m-d H:i:s', strtotime('-1 week')), 'one_week_good')),
            DB::raw(sprintf($byTypeTime, Comment::COMMENT_TYPE_MEDIUM, date('Y-m-d H:i:s', strtotime('-1 week')), 'one_week_medium')),
            DB::raw(sprintf($byTypeTime, Comment::COMMENT_TYPE_POOR, date('Y-m-d H:i:s', strtotime('-1 week')), 'one_week_poor')),

            DB::raw(sprintf($byTime, date('Y-m-d H:i:s', strtotime('-1 month')), 'one_month_all')),
            DB::raw(sprintf($byTypeTime, Comment::COMMENT_TYPE_GOOD, date('Y-m-d H:i:s', strtotime('-1 month')), 'one_month_good')),
            DB::raw(sprintf($byTypeTime, Comment::COMMENT_TYPE_MEDIUM, date('Y-m-d H:i:s', strtotime('-1 month')), 'one_month_medium')),
            DB::raw(sprintf($byTypeTime, Comment::COMMENT_TYPE_POOR, date('Y-m-d H:i:s', strtotime('-1 month')), 'one_month_poor')),

            DB::raw(sprintf($byTime, date('Y-m-d H:i:s', strtotime('-6 month')), 'half_all')),
            DB::raw(sprintf($byTypeTime, Comment::COMMENT_TYPE_GOOD, date('Y-m-d H:i:s', strtotime('-6 month')), 'half_good')),
            DB::raw(sprintf($byTypeTime, Comment::COMMENT_TYPE_MEDIUM, date('Y-m-d H:i:s', strtotime('-6 month')), 'half_medium')),
            DB::raw(sprintf($byTypeTime, Comment::COMMENT_TYPE_POOR, date('Y-m-d H:i:s', strtotime('-6 month')), 'half_poor')),

            DB::raw(sprintf($byTimeLt, date('Y-m-d H:i:s', strtotime('-6 month')), 'half_before_all')),
            DB::raw(sprintf($byTypeTimeLt, Comment::COMMENT_TYPE_GOOD, date('Y-m-d H:i:s', strtotime('-6 month')), 'half_before_good')),
            DB::raw(sprintf($byTypeTimeLt, Comment::COMMENT_TYPE_MEDIUM, date('Y-m-d H:i:s', strtotime('-6 month')), 'half_before_medium')),
            DB::raw(sprintf($byTypeTimeLt, Comment::COMMENT_TYPE_POOR, date('Y-m-d H:i:s', strtotime('-6 month')), 'half_before_poor')),

        ])->where([
            ['to_id', '=', $userId],
            ['from_type', '=', Comment::FROM_TYPE_DEALER]
        ])->first();
    }

    /**
     * 获取用户ID
     *
     * @return mixed
     * @author dch
     */
    protected function userId()
    {
        if ($userId = get_user_session_info('user_uuid')) {
            return $userId;
        }

        Log::error(sprintf('用户ID未获取到:userId , get_user_session_info:%s', print_r(get_user_session_info(), true)));
        throw new LogicException('用户ID未获取到');
    }
}
