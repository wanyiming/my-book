/**
 * Created by longyunrui on 2016/11/4.
 * 我使用的百度编辑器加载配置，在编辑器系统js之后加载即可
 */
function setUeEditorObject($object,_token,_toolbars) {
    //实例化编辑器
    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
    var _new_toolbars = _toolbars || ['fullscreen', 'source', 'undo', 'redo', 'bold','italic','indent','insertorderedlist','simpleupload','insertimage','forecolor','backcolor','inserttable','lineheight','link','fontfamily','fontsize','paragraph','emotion','searchreplace','pagebreak'];

    var ue = UE.getEditor($object,{
        wordCount:true,
        enableAutoSave:false,
        toolbars: [
            _new_toolbars
        ]
    });
    ue.ready(function() {
        ue.execCommand('serverparam', '_token', _token);//此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
    });


}
