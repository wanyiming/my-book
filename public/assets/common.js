/**
 * 提交类，不完善，仅供提交form型
 * @param $object 表单 Class  或者 id  传入方式：$(".class")
 * @param methodType 请求方式  get  post
 * @param isCallback  是否设置回调函数，设置则回调；
 * @param requestUrl  此url作为操作数据信息，以及分页等情况；
 * @param $param  请求的操作
 * @private
 */
function _fromSubmit($object,methodType,isCallback,requestUrl, param){
    layer.load(3);
    var _url = '';
    if (requestUrl) {
        _url = requestUrl;
    } else {
        _url = $object.attr('action');
    }
    $.ajax({
        type : methodType,
        url : _url,
        data : param ? param : $object.serialize(),
        dataType : 'json',
        success : function(responeData){
            layer.closeAll('loading');
            if (isCallback) {
                isCallback(responeData);//调用回调函数
            } else {
                if (responeData.status != 1) {
                    layer.msg(responeData.msg,{icon: 2});return false;
                }
                layer.msg(responeData.msg,{icon: 1},function(){
                    if (!responeData.data.url) {
                        responeData.data.url = location.href;
                    }
                    location.href = responeData.data.url;
                });
            }
        },error : function(){
            layer.closeAll();
            layer.msg('请求超时',{icon:2});
        }
    });
}

/**
 * 获取图片的高宽度
 * @param url 图片地址
 * @param callback 匿名回调
 * @returns {*}
 */
function getImageWidth(url,callback){
    var img = new Image();
    img.src = url;
    // 如果图片被缓存，则直接返回缓存数据
    if(img.complete){
        return callback(img.width, img.height);
    }else{
        // 完全加载完毕的事件
        img.onload = function(){
            return callback(img.width, img.height);
        }
    }
}


$(".recommendObjectSubmit").on('click',function(){
    var objType = $(this).attr('data-type');
    var objId = $(this).attr('data-id');
    if(!objId || !objType) {
        layer.alert('请求错误',{icon:2}); return false;
    }
    layer.open({
        type: 2,
        title: '信息推荐',
        shadeClose: true,
        shade: false,
        maxmin: false, //开启最大化最小化按钮
        area: ['893px', '600px'],
        content: ['/admin/recommend/edit/'+objType+'/'+objId]
    });
});
