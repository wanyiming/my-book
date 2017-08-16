<?php
/**
 * Created by PhpStorm.
 * User: long-yun-rui
 * Date: 2017/2/16
 * Time: 10:17
 * Description: 后台路由表
 */
Route::group(['namespace' => 'Admin', 'middleware' => ['admin.rbac'], 'prefix' => 'admin'], function () {
    // 已登录跳转方法,首页免权限
    Route:: get('/', 'IndexController@index')->name('admin.index');

    Route::get('sendxml', 'IndexController@sendSitemapXml');

    // 节点菜单管理
    Route::get('node','NodeController@lists')->name('admin.node');
    Route::post('node','NodeController@save')->name('admin.node.save');
    Route::get('node/add','NodeController@add')->name('admin.node.add');
    Route::get('node/{nodeId}/edit','NodeController@edit')->name('admin.node.edit')->where('nodeId','\d+');
    Route::post('node/{nodeId}/delete','NodeController@destroy')->name('admin.node.delete')->where('nodeId','\d+');
    Route::get('node/search','NodeController@search')->name('admin.node.search');

    // 配置管理
    Route::get('setting','SettingController@index')->name('admin.setting');
    Route::get('setting/create','SettingController@create')->name('admin.setting.create');
    Route::get('setting/{name}/edit','SettingController@edit')->name('admin.setting.edit');
    Route::post('setting/store','SettingController@store')->name('admin.setting.store');
    Route::post('setting/{name}/delete','SettingController@destroy')->name('admin.setting.delete');

    /**
     * 授权访问
     */
    Route::group(['middleware' => 'admin.safe'], function () {


        // 可以手动切换的后台上传文件调用链接
        Route::post('file-upload', 'UploadController@upload')->name('admin.file.upload');

        // 修改管理员密码
        Route::get('access/password_edit', 'AccessController@passwordEdit')->name('admin.access.password_edit');                    //修改密码
        Route::post('access/password_save', 'AccessController@passwordSave')->name('admin.access.password_save');                   //保存密码

        // 角色组管理
        Route::get('role/lists', 'RoleController@lists')->name('admin.role.lists');
        Route::get('role/edit/{id?}', 'RoleController@edit')->where('id', '\d{1,10}')->name('admin.role.edit');
        Route::get('role/add', 'RoleController@add')->name('admin.role.add');
        Route::post('role/save', 'RoleController@save')->name('admin.role.save');

        // 系统用户管理
        Route::get('admin_list/index', 'AdminListController@index')->name('admin.admin_list.index');
        Route::get('admin_list/edit/{id?}', 'AdminListController@edit')->where('id', '\d{1,10}')->name('admin.admin_list.edit');
        Route::get('admin_list/add', 'AdminListController@add')->name('admin.admin_list.add');
        Route::post('admin_list/save_admin_list', 'AdminListController@save')->name('admin.admin_list.save');
        Route::post('admin_list/admin_list_del', 'AdminListController@adminDelete')->name('admin.admin_list.delete');    //删除
        Route::post('admin_list/save_status', 'AdminListController@saveStatus')->name('admin.admin_list.save_status');    //锁定
        Route::post('admin_list/password_reset', 'AdminListController@passwordReset')->name('admin.admin_list.password_reset');    //重置密码

        // 后台员工职位管理
        Route::get('position/position_list', 'PositionController@position_list')->name('admin.position.position_list');
        Route::get('position/position_edit/{id?}', 'PositionController@positionEdit')->where('id', '\d{1,10}')->name('admin.position.position_edit');
        Route::get('position/position_add', 'PositionController@positionAdd')->name('admin.position.position_add');
        Route::post('position/save_position', 'PositionController@savePosition')->name('admin.position.save_position');

        // 网站管理
        Route::get('site/home','SiteController@index')->name('admin.site.home');

        // 友情连接
        Route::get('friendly_link/lists','FriendlyLinkController@lists')->name('admin.friendly_link.lists');
        Route::get('friendly_link/add','FriendlyLinkController@add')->name('admin.friendly_link.add');
        Route::get('friendly_link/edit/{id}','FriendlyLinkController@edit')->name('admin.friendly_link.edit');
        Route::get('friendly_link/examine/{id}','FriendlyLinkController@examine')->name('admin.friendly_link.examine');
        Route::post('friendly_link/append','FriendlyLinkController@append')->name('admin.friendly_link.append');
        Route::post('friendly_link/save','FriendlyLinkController@save')->name('admin.friendly_link.save');
        Route::post('friendly_link/change_status','FriendlyLinkController@change_status')->name('admin.friendly_link.change_status');
        Route::post('friendly_link/del','FriendlyLinkController@del')->name('admin.friendly_link.del');

        // 推荐
        Route::get('recommend/edit/{objtype}/{objid}','RecommendController@edit')->where('objtype','\d')->where('objid','\d{1,10}')->name('admin.recommend.edit');
        Route::post('recommend/save','RecommendController@save')->name('admin.recommend.save');

        // 敏感词
        Route::get('sensitive/index', 'SensitiveController@index')->name('admin.sensitive.index');// 列表
        Route::get('sensitive/edit', 'SensitiveController@edit')->name('admin.sensitive.edit');//保存
        Route::get('sensitive/edit_status', 'SensitiveController@editStatus')->name('admin.sensitive.status');//修改信息状态
        Route::post('sensitive/save', 'SensitiveController@save')->name('admin.sensitive.save');//保存敏感词信息
        Route::get('sensitive/sendfile', 'SensitiveController@sendfile')->name('admin.sensitive.sendfile');//生成静态文件

        // SEO设置
        Route::get('seo/rule_list', 'SeoController@ruleList')->name('admin.seo.lists');
        Route::get('seo/rule_add', 'SeoController@ruleAdd')->name('admin.seo.add');
        Route::get('seo/rule_edit/{id}', 'SeoController@ruleEdit')->name('admin.seo.edit');
        Route::post('seo/rule_save', 'SeoController@ruleSave')->name('admin.seo.save');

        // 关键词
        Route::get('keyword/index','KeywordController@lists')->name('admin.keyword.lists');    // 列表
        Route::get('keyword/edit','KeywordController@edit')->name('admin.keyword.edit');    //编辑页面
        Route::get('keyword/clear','KeywordController@clear')->name('admin.keyword.clear');    //跟新缓存
        Route::post('keyword/edit_status','KeywordController@editStatus')->name('admin.keyword.status');    // 修改状态
        Route::post('keyword/save','KeywordController@save')->name('admin.keyword.save');    // 保存关键词
        Route::get('search_key/lists','SearchKeyController@lists')->name('admin.search_key.lists');// 关键词

        // 广告管理
        Route::get('ad/position_list', 'AdController@positionList')->name('admin.ad.position_list');
        Route::get('ad/position_add', 'AdController@positionAdd')->name('admin.ad.add');
        Route::get('ad/position_edit/{id}', 'AdController@positionEdit')->name('admin.ad.edit');
        Route::post('ad/position_save', 'AdController@positionSave')->name('admin.ad.save');
        Route::post('ad/position_delete', 'AdController@positionDelete')->name('admin.ad.del');
        Route::get('ad/position_detail/{id}', 'AdController@positionDetail')->name('admin.ad.detail');
        Route::post('ad/ad_save', 'AdController@adSave')->name('admin.ad.ad_save');
        Route::post('ad/position_edit_status', 'AdController@adStatusEdit')->name('admin.ad.status'); //修改广告状态

        // 小说类目管理
        Route::get('book_type/lists','BookTypeController@lists')->name('admin.book_type.lists');
        Route::post('book_type/save','BookTypeController@save')->name('admin.book_type.save');
        Route::get('book_type/add','BookTypeController@add')->name('admin.book_type.add');
        Route::get('book_type/{id}/edit','BookTypeController@edit')->name('admin.book_type.edit')->where('id','\d+');
        Route::post('book_type/{id}/del','BookTypeController@del')->name('admin.book_type.del')->where('id','\d+');

        // 小说书本管理
        Route::get('books/lists', 'BooksController@lists')->name('admin.books.lists');
        Route::get('books/create', 'BooksController@create')->name('admin.books.create');
        Route::post('books/store', 'BooksController@store')->name('admin.books.store'); // 保存
        Route::get('books/edit/{id}', 'BooksController@edit')->name('admin.books.edit'); // 修改
        Route::post('books/operation', 'BooksController@operation')->name('admin.books.operation'); // 操作状态

        // 书本内容管理
        Route::get('books/chapter/{uuid}','BooksChapterController@lists')->name('admin.books.chapter'); // 列表
        Route::get('books/chapter_create/{uuid}', 'BooksChapterController@create')->name('admin.chapter.create'); // 创建
        Route::get('books/chapter_edit/{id}', 'BooksChapterController@edit')->name('admin.chapter.edit'); // 编辑
        Route::post('books/chapter_save', 'BooksChapterController@store')->name('admin.chapter.save'); // 保存
        Route::post('books/chapter_del', 'BooksChapterController@operation')->name('admin.chapter.del'); // 删除

        //评价管理
        Route::get('comment/index','CommentController@index')->name('admin.comment.index'); // 审核列表
        Route::post('comment/edit','CommentController@edits')->name('admin.comment.edit_status'); // 修改评价状态

        // 留言和加盟申请
        Route::get('joinus/index','JoinController@index')->name('admin.join.index');
        Route::post('joinus/change_status','JoinController@change_status')->name('admin.join.change.status');

        // 用户列表
        Route::get('user/index', 'UserController@lists')->name('admin.user.index'); // 列表
        Route::post('user/chang_status', 'UserController@setStatus')->name('admin.user.chang_status'); // 列表
    });
});

/**
 * 公共权限,不用登录即可访问
 */
Route::group(['prefix' => 'admin','middleware'=>'admin.safe', 'namespace' => 'Admin'], function () {

    // 自定义登录方法
    Route::group(['middleware' => 'admin.haslogin'], function () {
	    Route::get('public/login/hkanWr2IAj1jrPqV', 'PublicController@login')->name('admin_login');
        Route::get('public/login/hkanWr2IAj1jrPqV', 'PublicController@login')->name('admin.public.login'); // 登录地址
        Route::post('public/login', 'PublicController@postLogin')->name('admin.public.post.login'); // 提交登录
    });
    Route::get('public/logout', 'PublicController@logout')->name('admin.public.logout');
    // 附件参数配置
    Route::get('attachment/edit', 'AttachmentController@edit')->name('admin.attachment.edit');
    Route::post('attachment/save', 'AttachmentController@save')->name('admin.attachment.save');
});