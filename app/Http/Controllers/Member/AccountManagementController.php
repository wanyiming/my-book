<?php

namespace App\Http\Controllers\Member;

use App\Models\SysThumbnailRule;
use App\Models\User;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 *  账户管理相关
 * @author  liufangyuan
 * Class AccountManagementController
 * @package App\Http\Controllers\Member
 */
class AccountManagementController extends Controller
{
    /**
     * 用户基本信息
     * @author: liufangyuan
     * @date: 2016-12-8
     */
    public function basicInformation()
    {
        //获取用户基本信息
        $accountInfo = DB::table('user_association as u')->where('u.uuid','=',get_user_session_info('user_uuid'))->join('home_user as h','h.user_uuid','=','u.uuid')->first();
        \SEO::setTitle('基本信息');
        return view('member.accountManagement.basicInformation',compact('accountInfo'));
    }

    /**
     * 修改用户基本信息
     * @author: liufangyuan
     * @date: 2016-12-8
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editBasicInfo ()
    {
        //获取用户基本信息
        $accountInfo = DB::table('user_association as u')->where('u.uuid','=',get_user_session_info('user_uuid'))->join('home_user as h','h.user_uuid','=','u.uuid')->first();
        \SEO::setTitle('修改-基本信息');
        return view('member.accountManagement.editBasicInfo',compact('accountInfo'));
    }

    /**
     * 修改用户头像
     * @author: liufangyuan
     * @date: 2016-12-8
     */
    public function editAvatar(SysThumbnailRule $thumbnailRule)
    {
        //获取用户头像
        $headImage = DB::table('user_association as u')->where('u.uuid','=',get_user_session_info('user_uuid'))->join('home_user as h','h.user_uuid','=','u.uuid')->value('head_image');
        \SEO::setTitle('头像照片');
        return view('member.accountManagement.editAvatar', compact('headImage'));
    }


    /**
     * 头像修改
     * @author: liufangyuan
     * @date: 2016-12-8
     * @param Request $request
     * @param DisFile $disFile
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function avatarSave(Request $request)
    {
        try {
            $fileId = $request->get('file_id');
            if (empty($fileId)) {
                return json_encode(['status'=>-1,'msg'=>'很抱歉，上传失败，请稍后再试']);
            }
            $result = DB::table('home_user')->where('user_uuid',get_user_session_info('user_uuid'))->update(['head_image'=>$fileId]);
            if ($result !== false){//更新头像成功，获取不同宽高的图片
                $bigImage = qiniu_domain($fileId,['w'=>'220','h'=>'220']);
                $inImage = qiniu_domain($fileId,['w'=>'120','h'=>'120']);
                $smallImage = qiniu_domain($fileId,['w'=>'80','h'=>'80']);
                set_user_session_info('head_image', $fileId, User::MEMBER);
                session()->save();
                return json_encode(['status'=>1,'bigImage'=>$bigImage,'inImage'=>$inImage,'smallImage'=>$smallImage]);
            }else {
                return json_encode(['status'=>-1,'msg'=>'很抱歉，上传失败，请稍后再试！']);
            }
        } catch (Exception $e) {
            Log::warning($e);
        } catch (Throwable $e) {
            Log::warning($e);
        }
        return response(['error' => 0]);
    }

    /**
     * 保存基本信息
     * @author: liufangyuan
     * @date: 2016-12-8
     * @param Request $request
     */
    public function infoSave (Request $request)
    {
        $data = $request->all();
        $saveArray = [];
        //验证请求数据
        if (empty($data['nick_name'])){
            return response_error('请输入用户昵称');
        }

        if (mb_strlen($data['nick_name'],'utf8')>15){
            return response_error('输入的昵称字数不得超过15个字');
        }
        $saveArray['nick_name'] = $data['nick_name'];
        if (!empty($data['qq'])){
            if (!preg_match("/^[1-9]\d{4,10}$/",$data['qq'])){
                return response_error('输入的qq格式不正确');
            }
            $saveArray['qq'] = $data['qq'];
        }
        if (!empty($data['email'])){
            if (!validate($data['email'],'e')){
                return response_error('输入的邮箱格式不正确');
            }
            $saveArray['email'] = $data['email'];
        }
        $result = DB::table('home_user')->where('user_uuid',get_user_session_info('user_uuid'))->update($saveArray);
        if ($result !== false){
            return response_success('','修改基本信息成功');
        }else {
            return response_error('系统繁忙，请稍后再试');
        }
    }

    /**
     * get账户安全页面
     * @author: liufangyuan
     * @date: 2016-12-8
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function safety()
    {
        \SEO::setTitle('账户安全');
        return view('member.accountManagement.safety');
    }
}
