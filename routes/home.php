<?php
/**
 * Created by PhpStorm.
 * User: long-yun-rui
 * Date: 2017/2/20
 * Time: 16:40
 * Description: 前台路由表
 */

Route::group(['namespace' => 'Home'], function () {

    Route::get('/',"IndexController@index")->name('home.wap.index');// 网站首页
    Route::get('/book/{id}.html', "BookController@index")->name('home.book.detaile');

    // 用户注册
    Route::get('register','RegisterController@index')->name('register');//注册页面
    Route::post('home/passport/register', 'RegisterController@getPostCaptcha')->name('passport.register'); // 提交注册信息

    // 主页基本信息
    Route::get('jiancai','IndexController@jiancai')->name('home.jiancai'); // 建材主页
    Route::get('jiaju','IndexController@jiaju')->name('home.jiaju');// 家具主页
    Route::get('shebei','IndexController@shebei')->name('home.shebei');// 设备主页
    Route::get('changjia','ManufacturerController@index')->name('home.manufacturer.index'); // 厂家招商加盟首页
    Route::get('dealers','DealerController@index')->name('home.dealer.index'); // 经销商招商加盟首页
    Route::get('manager','MemberController@index')->name('home.project.manager.index'); // 经销商招商加盟首页
    Route::post('jm/save-apply','JoinController@saveApply')->name('home.jm.save.apply'); // 保存加盟信息
    Route::get('home/user/info','IndexController@userInfo')->middleware('sso')->name('home.user.info'); // 获取前台页面的top_bar信息


    //协议，友情链接
    Route::get('protocol_information/information/{id?}','ProtocolInformationController@show')->name('home.protocol_information.information');
    Route::get('protocol_information/link','ProtocolInformationController@link')->name('home.protocol_information.link');

    // 验证码
    Route::any('captcha-test', function()
    {
        $url = captcha_src();
        $data['url'] = $url;
        $data['status'] = 1;
        return $url;
    });
});

/**
 * 前台登录（普通会员，厂商，经销商）
 */
Route::group(['namespace' => 'Home'],function (){

    // 普通会员
    Route::get('home/login','LoginController@homeLogin')->name('home.login');// 普通会员登录
    Route::get('home/register','RegisterController@index')->name('home.register');// 会员注册
    Route::post('home/post_home_login','LoginController@getPostHomeLogin')->name('home.post_home_login');// 会员登录
    Route::post('home/get_code','LoginController@DynamicCode')->name('home.get_code');// 手机登录获取动态码
    // 找回密码
    Route::get('home/get_back_password','GetBackPasswordController@index')->name('home.dealer_get_back_password');// 经销商找回密码
    Route::post('home/get_back_password/get_mobile_code','GetBackPasswordController@getMobileCode')->name('home.get_back_password.get_mobile_code');// 发送手机验证码
    Route::post('home/get_back_password/verification_step_one','GetBackPasswordController@verificationStepOne')->name('home.get_back_password.verification_step_one');// 找回密码验证第一步
    Route::get('home/get_back_password/firm_get_step_two','GetBackPasswordController@getStepTwo')->name('home.firm_get_step_two');
    Route::get('home/get_back_password/dealer_get_step_two','GetBackPasswordController@getStepTwo')->name('home.dealer_get_step_two');
    Route::post('home/get_back_password/verification_step_two','GetBackPasswordController@verificationStepTwo')->name('home.get_back_password.verification_step_two');
    Route::get('home/get_back_password/dealer_get_step_three','GetBackPasswordController@getStepThree')->name('home.dealer_get_step_three');
    Route::get('home/get_back_password/firm_get_step_three','GetBackPasswordController@getStepThree')->name('home.firm_get_step_three');
});

// 勿动，全局上传下载 获取上传的凭证
Route::get('/uptoken',function () {
    return response(['uptoken' => (new \App\Libraries\Qiniu\FileManagement())->getUploadToken()]);
})->name('uptoken');
