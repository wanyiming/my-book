<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="keywords" content="admin, dashboard, bootstrap, template, flat, modern, theme, responsive, fluid, retina, backend, html5, css, css3">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="#" type="image/png">
    <title>AdminX</title>
    <link href="/assets/css/style.css" rel="stylesheet">
    <link href="/assets/css/style-responsive.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="/assets/js/html5shiv.js"></script>
    <script src="/assets/js/respond.min.js"></script>
    <![endif]-->
</head>
<body class="sticky-header" style="background: #ffffff">
<section style="background: #ffffff">
    <div class="">
        <section class="panel">
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="clearfix">
                        <div class="btn-group">
                            <button id="" style="margin-bottom: 15px;" class="btn btn-default">
                                添加推荐 <i class="fa fa-plus"></i>
                            </button>
                        </div>
                        <div class="btn-group">
                            <button id="" style="margin-bottom: 15px;" class="btn btn-success recommend_save">
                                保存修改 <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="space15"></div>
                    <form action="{!! to_route('admin.recommend.save') !!}" method="post" class="recommendAave">
                        <input type="hidden" value="{{$objtype}}" name="objtype">
                        <input type="hidden" value="{{$objid}}" name="objid">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <div id="editable-sample_wrapper" class="dataTables_wrapper form-inline" role="grid">
                            <table aria-describedby="editable-sample_info" class="table table-striped table-hover table-bordered dataTable" id="editable-sample">
                                <thead>
                                <tr role="row">
                                    <th>id</th>
                                    <th>项目ID</th>
                                    <th>位置</th>
                                    <th>开始时间</th>
                                    <th>结束时间</th>
                                    <th>权重</th>
                                    <th>备注</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody aria-relevant="all" aria-live="polite" role="alert">
                                @foreach($data as $k=>$v)
                                    <tr class="odd">
                                        <td class="sorting_1">{{$v['id']}}</td>
                                        <td class=""><input type="text" class="form-control"  style="width: 20px" value="{{$v['object_id']}}" readonly></td>
                                        <td class=" ">
                                            <select name="object_position[]" style="" class="form-control"  id="">
                                                @foreach($object_position as $k=>$vs)
                                                    <option value="{{$k}}" @if($k == $v['object_position']) selected @endif>{{$vs}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class=" ">
                                            <input class="form-control" id="begin_time"  onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" value="{{isset($v['begin_at']) ? $v['begin_at'] : ''}}" name="begin_at[]" type="text">
                                        </td>
                                        <td class=" ">
                                            <input class="form-control end_time"  onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" value="{{isset($v['end_at']) ? $v['end_at'] : ''}}" name="end_at[]" type="text">
                                        </td>
                                        <td class=" ">
                                            <input class="form-control" value="{{isset($v['weight']) ? $v['weight'] : ''}}" name="weight[]" type="text">
                                        </td>
                                        <td class=" ">
                                            <input class="form-control" value="{{isset($v['object_remark']) ? $v['object_remark'] : ''}}" name="object_remark[]" type="text">
                                        </td>
                                        <td class=" ">
                                            <a href="javascript:;"><button class="btn btn-danger btn-xs" type="button"><i class="fa fa-trash-o"></i> 删除</button></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</section>
<script src="/assets/js/jquery-1.10.2.min.js"></script>
<script src="/assets/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="/assets/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/laydate/laydate.js"></script>
<script src="/assets/js/modernizr.min.js"></script>
<script src="/layer/layer.js"></script>
<script src="/assets/js/jquery.nicescroll.js"></script>
<script>
    $(function(){
        var position = '{!! json_encode($object_position) !!}';
        position = eval("("+position+")");
        $(".btn-default").click(function(){
            var _str = '<tr class="odd"><td class="sorting_1">&nbsp;</td><td class=""><input type="text" class="form-control"  style="width: 20px" value="{{$objid}}" readonly></td>';
            _str += '<td class=" "><select name="object_position[]" style="" class="form-control"  id=""><option value="0" selected>--请选择</option>';
            for (var k in position) {
                _str += ' <option value="'+k+'">'+position[k]+'</option>';
            }
            _str += '</select></td><td class=" "><input class="form-control" id="begin_time"  onclick="laydate({istime: true, format: \'YYYY-MM-DD hh:mm:ss\'})" name="begin_at[]" type="text">' +
                '</td><td class=" "><input class="form-control end_time"  onclick="laydate({istime: true, format: \'YYYY-MM-DD hh:mm:ss\'})"  name="end_at[]" type="text">' +
                '</td><td class=" "> <input class="form-control"  name="weight[]" type="text"> </td><td class=" "> <input class="form-control" name="object_remark[]" type="text">' +
                '</td><td class=" "><a href="javascript:;"><button class="btn btn-danger btn-xs" type="button"><i class="fa fa-trash-o"></i> 删除</button></a></td></tr>';
            $("tbody").append(_str);
        });
    });
    //删除
    $(document).on('click','button.btn-danger',function(){
        $(this).parents('tr').remove();
    });
    //保存修改
    $(document).on('click','button.recommend_save',function(){
        $.post("{!! to_route('admin.recommend.save') !!}",$('.recommendAave').serialize(),function (data){
            if (data.status != -1) {
                layer.msg(data.data,{icon:1});
                window.location.href = location.href;
            } else {
                layer.msg(data.msg,{icon:2});
            }
        },'json').error(function(){
            layer.msg('请求异常', {icon:2});
        });
    });
</script>
</body>
</html>
