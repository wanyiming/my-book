/**
 * Created by 刘方圆 on 2016/9/13.
 */

    /**
     * 后台通用ajax提交（post）
     */

    function ajaxpostnew(_this,type){
        var target_form = _this.attr('target-form');//要提交的form表单class
        var form = $('.' + target_form);//表单对象
        //表单提交
        var target = form.attr('action'); //要提交的url
        var datas = form.serialize();//要提交的form表单数据
        _this.addClass('disabled').attr('autocomplete','off').prop('disabled',true);//防止重复提交
        //提交表单
        if(type == 1){//get方式提交
            $.get(target,datas,function(data){
                if(data.status){
                    if(data.status == 0){//成功
                        layer.alert(data.msg,{
                            skin:'layui-layer-lan',closeBtn:0,shift:4 //动画类型
                        });
                        _this.removeClass('disabled').prop('disabled',false);
                        location.reload();
                    }else{//失败
                        layer.alert(data.msg,{
                            skin:'layui-layer-lan',closeBtn:0,shift:4 //动画类型
                        });
                        _this.removeClass('disabled').prop('disabled',false);
                    }
                }else{
                    layer.alert(data.msg,{
                        skin:'layui-layer-lan',closeBtn:0,shift:4 //动画类型
                    });
                    _this.removeClass('disabled').prop('disabled',false);
                }
            },'json');
        }else{//post方式提交
            $.post(target,datas,function(data){
                if(data.status){
                    if(data.status == 0){//成功
                        //墨绿深蓝风
                        layer.alert(data.msg,{
                            skin:'layui-layer-lan',closeBtn:0,shift:4 //动画类型
                        });
                        _this.removeClass('disabled').prop('disabled',false);
                        location.reload();
                    }else{//失败
                        layer.alert(data.msg,{
                            skin:'layui-layer-lan',closeBtn:0,shift:4 //动画类型
                        });
                        _this.removeClass('disabled').prop('disabled',false);
                    }
                }else{
                    layer.alert(data.msg,{
                        skin:'layui-layer-lan',closeBtn:0,shift:4 //动画类型
                    });
                    _this.removeClass('disabled').prop('disabled',false);
                }
            },'json');
        }
        return false;
    }
// ajax(post)参数型
function ajax_post(_this,info) {
    var url = _this.attr('url');
    layer.confirm(info, {
        btn: ['确认','取消'] //按钮
    }, function(){
        $.post(url,'',function(result){
            if (result.status == 1){
                layer.alert(result.msg,{
                    skin:'layui-layer-lan',closeBtn:0,shift:4 //动画类型
                });
                location.reload();

            }else {
                layer.alert(result.msg,{
                    skin:'layui-layer-lan',closeBtn:0,shift:4 //动画类型
                });
            }
        });
    }, function(index){
        layer.close(index);
    });
}
//ajax(get)
function ajax_get(_this,info){
    var url = _this.attr('url');
    layer.confirm(info, {
        btn: ['确认','取消'] //按钮
    }, function(){
        $.get(url,'',function(result){
            if (result.status == 1){
                layer.alert(result.msg,{
                    skin:'layui-layer-lan',closeBtn:0,shift:4 //动画类型
                });
                location.reload();
            }else {
                layer.alert(result.msg,{
                    skin:'layui-layer-lan',closeBtn:0,shift:4 //动画类型
                });
            }
        });
    }, function(index){
        layer.close(index);
    });
}

$(function(){
    //checked_admin_header_nav('.custom-nav .three-menu-list .sub-menu-list li a','admin');
    var navIdStr = $(".sub-menu-list .active").closest(".menu-list").attr("id");
    if(navIdStr){
        var navIdNum =  navIdStr.substr(5);
        $("#"+navIdNum).trigger("click");
    }

    //ajax post 提交
    $('body').on('click','.ajax-post',function(){
        var form = $('.form-datas');
        var url = form.get(0).action;
        var query = form.serialize();
        var that = this;
        $(that).addClass('disabled').attr('autocomplete','off').prop('disabled',true);
        $.post(url,query,function(datas){
            if(datas.status==1){
                $(that).removeClass('disabled').prop('disabled',false);
                updateAlert(datas.info + ' 页面即将自动跳转~','alert-success',datas.url);
            }else{
                $(that).removeClass('disabled').prop('disabled',false);
                updateAlert(datas.info);
            }
        });
        return false;
    });
    /**
     * 信息提示
     * @param text 提示语句
     * @param c 成功样式，当ajax返回成功提示需要用到，成功样式：alert-success
     * @param u 是否又跳转地址，有的话，当提示框消失的时候动跳转
     */
    window.updateAlert = function (text,c,u) {
        var top_alert = $('#top-alert');
        if(text){
            if(top_alert.has('alert-success')){
                top_alert.removeClass('alert-success').addClass('alert-block alert-danger');
            }
            if ( c ) {
                top_alert.removeClass('alert-block alert-danger').addClass(c);
                top_alert.find('.msg').text('消息成功提示!');
            }
            top_alert.find('.message').text(text);
            top_alert.show().slideDown(200);
            setTimeout(function(){
                top_alert.hide();
                if(u){
                    location.href = u;
                }
            },1500);
        }
    };
    //提示界面自动消失
    var alert_msg = $('.alert-msg');
    if(alert_msg.length){
        setTimeout(function(){
            alert_msg.hide();
        },1500);
    }

});