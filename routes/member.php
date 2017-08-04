<?php
/**
 * Created by PhpStorm.
 * Description: 个人中心
 */
Route::group(['namespace' => 'Member', 'prefix' => 'member','middleware'=>['home.login']],function (){

    // 收藏，推荐
    Route::post('book/operation', 'OperationController@hander')->name('member.operation.hander');


    // 用户基本信息
    Route::get('account/safety','AccountManagementController@safety')->name('member.account.safety');//获取账户安全页面
    Route::get('account/info','AccountManagementController@basicInformation')->name('member.account.info');	// 前台账户基本信息
    Route::get('account/edit_info','AccountManagementController@editBasicInfo')->name('member.account.edit_info');// 修改基本信息
    Route::post('account/info_save','AccountManagementController@infoSave')->name('member.account.info_save');// 保存基本信息
    Route::get('account/edit_avatar','AccountManagementController@editAvatar')->name('member.account.edit_avatar');// 修改头像
    Route::post('account/avatar_save','AccountManagementController@avatarSave')->name('member.account.avatar_save');// 保存头像

    // 信息收藏
    Route::get('collection/shop','CollectionController@shopLists')->name('member.collection.shop');//店铺收藏
    Route::get('collection/goods','CollectionController@goodsLists')->name('member.collection.goods');//商品收藏
    Route::post('collection/cancel','CollectionController@cancel')->name('member.collection.cancel');//取消关注
    Route::post('collection/add', 'CollectionController@objectAdd')->name('member.collection.add'); // 添加关注

    // 评价管理
    Route::get('comment/index','CommentController@index')->name('member.comment.index');// 评价管理
    Route::get('comment/lists','CommentController@lists')->name('member.comment.lists'); // 评价列表

});