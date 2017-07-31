
(function() {
    "use strict";

    // custom scrollbar

    $("html").niceScroll({styler:"fb",cursorcolor:"#65cea7", cursorwidth: '6', cursorborderradius: '0px', background: '#424f63', spacebarenabled:false, cursorborder: '0',  zindex: '1000'});

    $(".left-side").niceScroll({styler:"fb",cursorcolor:"#65cea7", cursorwidth: '3', cursorborderradius: '0px', background: '#424f63', spacebarenabled:false, cursorborder: '0'});


    $(".left-side").getNiceScroll();
    if ($('body').hasClass('left-side-collapsed')) {
        $(".left-side").getNiceScroll().hide();
    }



    // Toggle Left Menu
    jQuery('.menu-list > a').click(function() {

        var parent = jQuery(this).parent();
        var sub = parent.find('> ul');

        if(!jQuery('body').hasClass('left-side-collapsed')) {
            if(sub.is(':visible')) {
                sub.slideUp(200, function(){
                    parent.removeClass('nav-active');
                    jQuery('.main-content').css({height: ''});
                    mainContentHeightAdjust();
                });
            } else {
                visibleSubMenuClose();
                parent.addClass('nav-active');
                sub.slideDown(200, function(){
                    mainContentHeightAdjust();
                });
            }
        }
        return false;
    });

    function visibleSubMenuClose() {
        jQuery('.menu-list').each(function() {
            var t = jQuery(this);
            if(t.hasClass('nav-active')) {
                t.find('> ul').slideUp(200, function(){
                    t.removeClass('nav-active');
                });
            }
        });
    }

    //二级目录下存在三级目录时加载的js
    jQuery('.three-menu-list > a').click(function() {
        var parent = jQuery(this).parent();
        var sub = parent.find('> ul');

        if(!jQuery('body').hasClass('left-side-collapsed')) {
            if(sub.is(':visible')) {
                jQuery(this).find('i').attr('class','fa fa-plus-square');
                sub.slideUp(200, function(){
                    parent.removeClass('nav-active');
                    jQuery('.main-content').css({height: ''});
                    mainContentHeightAdjust();
                });
            } else {
                visibleSubMenuClose_three(parent.parent());
                parent.addClass('nav-active');
                jQuery(this).find('i').attr('class','fa fa-minus-square');
                sub.slideDown(200, function(){
                    mainContentHeightAdjust();
                });
            }
        }else{
            if(sub.is(':visible')) {
                jQuery(this).find('i').attr('class','fa fa-plus-square');
                sub.slideUp(200, function(){
                    parent.removeClass('nav-active');
                    jQuery('.main-content').css({height: ''});
                    mainContentHeightAdjust();
                });
            } else {
                visibleSubMenuClose_three(parent.parent());
                parent.addClass('nav-active');
                jQuery(this).find('i').attr('class','fa fa-minus-square');
                sub.slideDown(200, function(){
                    mainContentHeightAdjust();
                });
            }
        }
        return false;
    });

    function visibleSubMenuClose_three(_this) {
        _this.find('li').each(function() {
            var t = jQuery(this);
            if(t.hasClass('nav-active')) {
                t.find('> ul').slideUp(200, function(){
                    t.find('i').attr('class','fa fa-plus-square');
                    t.removeClass('nav-active');
                });
            }
        });
    }
    /************************************/

    function mainContentHeightAdjust() {
        // Adjust main content height
        var docHeight = jQuery(document).height();
        if(docHeight > jQuery('.main-content').height())
            jQuery('.main-content').height(docHeight);
    }

    //  class add mouse hover
    jQuery('.custom-nav > li').hover(function(){
        jQuery(this).addClass('nav-hover');
    }, function(){
        jQuery(this).removeClass('nav-hover');
    });

    /*小导航栏加载js*/
    /*jQuery('.custom-nav > li').hover(function(){
        if(jQuery(this).hasClass('nav-active')){							//如果选中的是当前导航栏
            if(jQuery('body').hasClass('left-side-collapsed')){				//如果是小导航栏的时候，将当前导航栏下的子栏目显示
                jQuery(this).find('.two-custom-nav .three-menu-list.nav-active > ul').css({display: 'block'});
            }
        }else{
            var _this = jQuery(this).find('.two-custom-nav .three-menu-list.nav-active');
            _this.removeClass('nav-active');								//将当前导航栏下的选中效果取消
            _this.find('i').attr('class','fa fa-plus-square');				//将子导航栏的图标有-号换成+号
        }
    }, function(){															//鼠标离开后
        if(jQuery(this).hasClass('nav-active')){							//如果离开前的导航栏是被选中的导航栏
            jQuery(this).find('.two-custom-nav .three-menu-list.nav-active > ul').css({display: 'block'});		//将子导航栏设为显示
        }else{
            var _this = jQuery(this).find('.two-custom-nav .three-menu-list.nav-active');
            _this.removeClass('nav-active');								//移除选中样式
            _this.find('i').attr('class','fa fa-plus-square');				//将子导航栏的图标有-号换成+号
            _this.find('> ul').attr('style','');
        }
    });*/
    /**/
    // Menu Toggle
    jQuery('.toggle-btn').click(function(){
        $(".left-side").getNiceScroll().hide();

        if ($('body').hasClass('left-side-collapsed')) {
            $(".left-side").getNiceScroll().hide();
        }
        var body = jQuery('body');
        var bodyposition = body.css('position');
        if(bodyposition != 'relative') {
            if(!body.hasClass('left-side-collapsed')) {						//如果是切换到小导航栏
                body.addClass('left-side-collapsed');
                jQuery('.custom-nav ul').attr('style','');
                jQuery('.two-custom-nav ul').attr('style','');

                jQuery(this).addClass('menu-collapsed');

            } else {
                body.removeClass('left-side-collapsed chat-view');				//去掉小导航栏样式

                var _this = jQuery('.custom-nav li.nav-active').find('.two-custom-nav .nav-active');	//检测选中的导航栏下面有没有选中子导航栏
                if(_this){
                    _this.find('ul').css({display: 'block'});					//显示被选中的子导航栏
                }

                jQuery(this).removeClass('menu-collapsed');

            }
        } else {
            if(body.hasClass('left-side-show'))
                body.removeClass('left-side-show');
            else
                body.addClass('left-side-show');

            mainContentHeightAdjust();
        }

    });


    searchform_reposition();

    jQuery(window).resize(function(){

        if(jQuery('body').css('position') == 'relative') {

            jQuery('body').removeClass('left-side-collapsed');

        } else {

            jQuery('body').css({left: '', marginRight: ''});
        }

        searchform_reposition();

    });

    function searchform_reposition() {
        if(jQuery('.searchform').css('position') == 'relative') {
            jQuery('.searchform').insertBefore('.left-side-inner .logged-user');
        } else {
            jQuery('.searchform').insertBefore('.menu-right');
        }
    }

    // panel collapsible
    $('.panel .tools .fa').click(function () {
        var el = $(this).parents(".panel").children(".panel-body");
        if ($(this).hasClass("fa-chevron-down")) {
            $(this).removeClass("fa-chevron-down").addClass("fa-chevron-up");
            el.slideUp(200);
        } else {
            $(this).removeClass("fa-chevron-up").addClass("fa-chevron-down");
            el.slideDown(200); }
    });

    $('.todo-check label').click(function () {
        $(this).parents('li').children('.todo-title').toggleClass('line-through');
    });

    $(document).on('click', '.todo-remove', function () {
        $(this).closest("li").remove();
        return false;
    });

    $("#sortable-todo").sortable();


    // panel close
    $('.panel .tools .fa-times').click(function () {
        $(this).parents(".panel").parent().remove();
    });



    // tool tips

    $('.tooltips').tooltip();

    // popovers

    $('.popovers').popover();



})(jQuery);