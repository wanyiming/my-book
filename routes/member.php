<?php
/**
 * Created by PhpStorm.
 * User: long-yun-rui
 * Date: 2017/2/28
 * Time: 15:03
 * Description: 普通会员，项目经理，高级项目经理路由表
 */
Route::group(['namespace' => 'Member', 'prefix' => 'member','middleware'=>['home.login','notify.automatic.update','sso']],function (){

    // 招标管理
    Route::get('tender/index','TenderController@index')->name('member.tender.index');
    Route::get('tender/add','TenderController@add')->name('member.tender.add');
    Route::get('tender/edit/{id}','TenderController@edit')->name('member.tender.edit');
    Route::get('tender/info/{id}','TenderController@info')->name('member.tender.info');
    Route::post('tender/choice','TenderController@choice')->name('member.tender.choice');
    Route::post('tender/closing','TenderController@closing')->name('member.tender.closing');
    Route::post('tender/save','TenderController@save')->name('member.tender.save');
    Route::post('tender/get_code', 'TenderController@getMobileCode')->name('member.tender.get_code');

    // 用户基本信息
    Route::get('account/safety','AccountManagementController@safety')->name('member.account.safety');//获取账户安全页面
    Route::get('account/info','AccountManagementController@basicInformation')->name('member.account.info');	// 前台账户基本信息
    Route::get('account/edit_info','AccountManagementController@editBasicInfo')->name('member.account.edit_info');// 修改基本信息
    Route::post('account/info_save','AccountManagementController@infoSave')->name('member.account.info_save');// 保存基本信息
    Route::get('account/edit_avatar','AccountManagementController@editAvatar')->name('member.account.edit_avatar');// 修改头像
    Route::post('account/avatar_save','AccountManagementController@avatarSave')->name('member.account.avatar_save');// 保存头像

    // 收货地址
    Route::get('shipping_address/index','ShippingAddressController@index')->name('member.shipping_address.index');// 设置收货地址页面
    Route::post('shipping_address/address_save','ShippingAddressController@addressSave')->name('member.shipping_address.address_save');// 保存收货地址
    Route::post('shipping_address/address_del','ShippingAddressController@addressDel')->name('member.shipping_address.address_del');// 删除收货地址
    Route::post('shipping_address/set_default','ShippingAddressController@setDefault')->name('member.shipping_address.set_default');// 设置默认地址

    // 交易管理
    Route::get('order/lists','MyOrderController@lists')->name('member.order.lists'); // 订单列表
    Route::get('order/show','MyOrderController@show')->name('member.order.show');//订单详情
    Route::post('order/cancel','MyOrderController@cancelOrder')->name('member.order.cancel');//取消订单
    Route::post('order/confirm_receipt','MyOrderController@confirmReceipt')->name('member.order.confirm_receipt');//确认收货
    Route::get('order/pay','MyOrderController@pay')->name('member.pay');//支付页面
    Route::any('order/post_pay','MyOrderController@postPay')->name('member.post_pay');//post请求支付
    Route::post('order/return_url','MyOrderController@returnUrl')->name('member.pay.return_url');//同步回调地址
    Route::post('order/comment/{orderSn?}','MyOrderController@productComment')->name('member.commend.product'); // 买家给商家评价

    // 信息收藏
    Route::get('collection/shop','CollectionController@shopLists')->name('member.collection.shop');//店铺收藏
    Route::get('collection/goods','CollectionController@goodsLists')->name('member.collection.goods');//商品收藏
    Route::post('collection/cancel','CollectionController@cancel')->name('member.collection.cancel');//取消关注
    Route::post('collection/add', 'CollectionController@objectAdd')->name('member.collection.add'); // 添加关注

    // 评价管理
    Route::get('comment/index','CommentController@index')->name('member.comment.index');// 评价管理
    Route::get('comment/lists','CommentController@lists')->name('member.comment.lists'); // 评价列表

    // 厂家产品列表
    Route::get('product/lists','ManufactorController@goodsLists')->name('member.maunfactor.product'); //厂家产品列表
    Route::get('product/detail/{id}','ManufactorController@detail')->name('member.maunfactor.product_detail'); //厂家产品详情
    Route::get('firm/lists','ManufactorController@firmLists')->name('member.firm.lists');//厂家列表
    Route::get('firm/show','ManufactorController@firmShow')->name('member.firm.show');//厂家介绍
    Route::get('firm/shop_lists/{uuid}','ManufactorController@shopGoods')->name('member.firm.shop_lists');//厂家内商品列表

    // 消息提醒
    Route::get('letter/index','LetterController@index')->name('member.letter.index'); // 默认为系统消息
    Route::post('letter/change-status','LetterController@change_status')->name('member.letter.change.status'); // 默认为系统消息
    Route::get('trade/index','LetterController@trade_index')->name('member.trade.index'); // 默认为系统消息
    Route::post('trade/change-status','LetterController@trade_change_status')->name('member.trade.change.status'); // 默认为系统消息

    //微信查询订单支付是否成功
    Route::post('pay/verify_pay_status','MyOrderController@verifyPayStatus')->name('member.pay.verify_pay_status');
});

/**
 * 普通会员订单操作
 */
Route::group(['namespace' => 'Dealer', 'prefix' => 'member','middleware' => ['home.login','notify.automatic.update','sso']],function (){
    Route::post('shopping/create_order','ShoppingCartController@createOrder')->name('member.shopping.created_order');//生成订单
});
