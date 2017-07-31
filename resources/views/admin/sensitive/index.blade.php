@extends('admin.layouts.base')
@section('styles')
    <style type="text/css">
        /*input[type='checkbox']{
            width: 50px;
            height: 50px;
        }*/
    </style>
@endsection
@section('scripts')
    <script>
        function del (id,status_) {
            if (parseInt(id)) {
                layer.confirm("是否确认操作该信息?",{icon:3},function(){
                    layer.load(3);
                    $.get("{{to_route('admin.sensitive.status')}}",{id:id,status:status_},function(data){
                        layer.closeAll('loading');
                        if (data.status > 0){
                            layer.msg(data.info,{icon:1});
                            setTimeout("location.reload()",3000);
                            return false;
                        } else {
                            layer.msg(data.info,{icon:2});
                        }
                    },'json').error(function(){
                        layer.msg('请求异常',{icon:2});
                    });
                });
            } else {
                layer.msg('请求参数错误，删除失败',{icon:2});
            }
        }
    </script>
@endsection
@section('content')
    <div class="wrapper">
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        信息列表
                    <span class="tools pull-right">
                        <a href="javascript:;" class="fa fa-chevron-down"></a>
                        <a href="javascript:;" class="fa fa-times"></a>
                     </span>
                    </header>
                    <div class="panel-body">
                        <div class="adv-table ">
                            <div class="clearfix">
                                <div class="btn-group">
                                    <a href="{{to_route('admin.sensitive.edit')}}">
                                        <button id="editable-sample_new" class="btn btn-primary">
                                            添加敏感词 <i class="fa fa-plus"></i>
                                        </button>
                                    </a>
                                    <a href="{{to_route('admin.sensitive.sendfile')}}">
                                        <button id="editable-sample_new" class="btn btn-warning">
                                            生成敏感词文件</i>
                                        </button>
                                    </a>
                                </div>
                                <div class="btn-group pull-right">
                                    <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">工具 <i class="fa fa-angle-down"></i>
                                    </button>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="{{url($_SERVER['REQUEST_URI'])}}">刷新</a></li>
                                        <li><a href="javascript:;">导出PDF</a></li>
                                        <li><a href="javascript:;">导出Excel</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="space15"></div>
                            <div id="editable-sample_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                <form action="" method="get">
                                    <div class="row">
                                        <div class="dataTables_length" id="editable-sample_length" style="min-height: 70px;">
                                            <div class="col-lg-2">
                                                <label for="" style="line-height: 30px"> <strong>信息总量：{{$data->total()}}</strong></label>
                                            </div>
                                            <div style="float: right; padding-right: 15px;">
                                                <div class="col-lg-2" style="width:auto;">
                                                    <label>模板名称: <input class="form-control" maxlength="20" aria-controls="editable-sample" name="title" value="{!! @htmlspecialchars(trim($_GET['title'])) !!}" type="text"></label>
                                                </div>
                                                <button class="btn btn-search" type="submit"><i class="fa fa-search-minus"></i>搜索</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <table aria-describedby="editable-sample_info" class="table table-striped table-hover table-bordered dataTable" id="editable-sample">
                                    <thead>
                                    <tr role="row">
                                        <th>id</th>
                                        <th>模板名称</th>
                                        <th>状态</th>
                                        <th>添加时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody aria-relevant="all" aria-live="polite" role="alert">
                                    @foreach($data as $k=>$v)
                                        <tr class="odd">
                                            <td class="  sorting_1">{{$v['id']}}</td>
                                            <td class=" "> {{$v['name']}}</td>
                                            <td class=" ">
                                                @foreach($status as $s=>$sv)
                                                    @if($s == $v['status'])
                                                        <label class="{{$sv['class']}}">{{$sv['name']}}</label>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td class=" ">{{$v['create_at']}}</td>
                                            <td class=" ">
                                            @if($v['status'] == \App\Models\SysSensitive::STATUS_NORMAL)
                                                    <a href="javascript:del('{{$v['id']}}',{{\App\Models\SysSensitive::STATUS_DISABLE}});"><button class="btn btn-default btn-xs" type="button"><i class="fa fa-ban"></i> 停用</button></a>
                                            @endif
                                            @if($v['status'] == \App\Models\SysSensitive::STATUS_DISABLE)
                                                <a href="javascript:del('{{$v['id']}}',{{\App\Models\SysSensitive::STATUS_NORMAL}});"><button class="btn btn-success btn-xs" type="button"><i class="fa fa-check"></i> 启用</button></a>
                                            @endif
                                            @if($v['status'] == \App\Models\SysSensitive::STATUS_NORMAL || $v['status'] == \App\Models\SysSensitive::STATUS_DISABLE)
                                                    <a href="javascript:del('{{$v['id']}}',{{\App\Models\SysSensitive::STATUS_DELETE}});"><button class="btn btn-danger btn-xs" type="button"><i class="fa fa-trash-o"></i> 删除</button></a>
                                                    <a href="{{to_route('admin.sensitive.edit')}}?id={{$v['id']}}"><button class="btn btn-warning btn-xs" type="button"><i class="fa fa-pencil"></i> 修改</button></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="dataTables_info" id="editable-sample_info"></div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="dataTables_paginate paging_bootstrap pagination">
                                            {!! $data->appends($where)->links('admin.layouts.page_html') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection