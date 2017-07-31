/**
 * Created by longyunrui on 2016/11/6.
 */
// var _article_type = eval({"2":{"id":2,"title":" \u88c5\u9970\u88c5\u4fee","en_title":"zszx","pid":1,"status":1,"updated_at":"2016-11-06 15:38:16","created_at":"2016-11-06 14:40:57","islast":1,"child":{"3":{"id":3,"title":"\u88c5\u9970\u88c5\u4fee\u5b50\u7c7b\u76ee","en_title":"zszxzlm","pid":2,"status":1,"updated_at":"2016-11-06 17:28:43","created_at":"2016-11-06 17:28:43","islast":1,"child":{"4":{"id":4,"title":"\u5b50\u7c7b\u76ee","en_title":"zlm","pid":3,"status":1,"updated_at":"2016-11-06 17:33:11","created_at":"2016-11-06 17:33:11","islast":1}}}}}});
$(function () {
    var _first_select = '';
    for (var  k in _article_type) {
        _first_select += '<option class="option" value="'+_article_type[k].id+'" _index="'+_article_type[k].id+'">'+_article_type[k].title+'</option>';
    }
    $(".first_type").append(_first_select);
});

$(function () {
    $('body').on('change','.first_type,.second_type',function () {
        var _class = $(this).attr('data-class');
        var _index = $(this).children(':selected').attr('_index');

        var _second_select = '<option value="">--请选择--</option>';
        if(_index){
            if (_class == 'first_type') {
                var _child = _article_type[_index].child;
                if(_child){
                    for  (var j in _child) {
                        _second_select += '<option class="option" value="'+j+'" _index="'+j+'">'+_child[j].title+'</option>';
                    }
                    $(this).parent().next().find('select').empty().append(_second_select);
                }
            } else {
                var parent_id = $(this).parents('.article_type').find('.first_type').children(':selected').attr('_index');
                var _child = _article_type[parent_id].child[_index].child;
                if (_child) {
                    for  (var k in _child) {
                        _second_select += '<option class="option" _index="'+k+'" value="'+k+'">'+_child[k].title+'</option>';
                    }
                    $(this).parent().next().find('select').empty().append(_second_select);
                }
            }
        }else{
            $(this).parent().nextAll().find('select').empty().append(_second_select);
        }
    })
});


//默认选中
function set_default_seleced(obj,first,second,third) {
    obj.find(".first_type>option").each(function(){  //遍历所有option
        if($(this).val()==first){
            $(this).attr('selected','selected');
        }
    });
    var _index = obj.find(".first_type>option:selected").val();//一级id
    if(_index){
        var _child = _article_type[_index].child;
        if(_child){
            var _second_select = '<option value="">--请选择--</option>';
            for(var j in _child) {
                _second_select += '<option value="'+j+'" _index="'+j+'">'+_child[j].title+'</option>';
            }
            //将下一级所有数据追加到下一级下拉框中
            obj.find(".first_type").parent().next().find('select').empty().append(_second_select);
            //如果需要选中第二级
            if(second){
                obj.find(".second_type>option").each(function(){  //遍历所有option
                    if($(this).val()==second){
                        $(this).attr('selected','selected');
                    }
                });
                var _two_id = obj.find(".second_type>option:selected").val();//二级id
                var _two_child = _article_type[_index].child[_two_id].child;
                //如果下一级存在值
                if(_two_child){
                    var _third_select = '<option value="">--请选择--</option>';
                    for  (var k in _two_child) {
                        _third_select += '<option _index="'+_two_child[k].id+'" value="'+_two_child[k].id+'">'+_two_child[k].title+'</option>';
                    }
                    //将下一级所有数据追加到下一级下拉框中
                    obj.find(".second_type").parent().next().find('select').empty().append(_third_select);
                    if(third){
                        obj.find(".third_type>option").each(function(){  //遍历所有option
                            if($(this).val()==third){
                                $(this).attr('selected','selected');
                            }
                        });
                    }
                }
            }
        }
    }
}


