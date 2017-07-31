@extends('admin.layouts.base')
@section('styles')
@endsection
@section('scripts')
    <script>
        function del (id,status_) {
            if (parseInt(id)) {
                layer.confirm("是否确认操作?",{icon:3},function(){
                    layer.load(3);
                    $.post("{{to_route('admin.keyword.status')}}",{id:id,status:status_,_token:'{!! csrf_token() !!}'},function(data){
                        layer.closeAll('loading');
                        if (data.status > 0){
                            layer.msg(data.data,{icon:1});
                            setTimeout("location.reload()",3000);
                            return false;
                        } else {
                            layer.msg(data.msg,{icon:2});
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
                        关键词列表
                    <span class="tools pull-right">
                        <a href="javascript:;" class="fa fa-chevron-down"></a>
                        <a href="javascript:;" class="fa fa-times"></a>
                     </span>
                    </header>
                    <div class="panel-body">
                        <div class="clearfix">
                            <div class="btn-group">
                                <a href="{{to_route('admin.keyword.edit')}}">
                                    <button id="editable-sample_new" class="btn btn-primary">
                                        添加关键词 <i class="fa fa-plus"></i>
                                    </button>
                                </a>
                            </div>
                            <div class="btn-group">
                                <a href="{{to_route('admin.search_key.lists')}}">
                                    <button id="editable-sample_new" class="btn btn-warning">
                                        查看关键词统计
                                    </button>
                                </a>
                            </div>
                            <div class="btn-group">
                                <a href="{{to_route('admin.keyword.clear')}}">
                                    <button id="editable-sample_new" class="btn btn-success">
                                        更新缓存
                                    </button>
                                </a>
                            </div>
                            <div class="btn-group pull-right">
                                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">工具 <i class="fa fa-angle-down"></i>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                    <li><a href="{{$_SERVER['REQUEST_URI']}}">刷新</a></li>
                                    <li><a href="javascript:;">导出PDF</a></li>
                                    <li><a href="javascript:;">导出Excel</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="adv-table editable-table ">
                            <div id="editable-sample_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                <form action="" method="get">
                                    <div class="row">
                                        <div class="dataTables_length" id="editable-sample_length" style="min-height: 70px;">
                                            <div class="col-lg-2">
                                                <label for="" style="line-height: 30px"> <strong>信息总量：{{$data->total()}}</strong></label>
                                            </div>
                                            <div style="float: right; padding-right: 15px;">
                                                <div class="col-lg-2" style="width:auto;">
                                                    <label>关键词: <input class="form-control" maxlength="20" aria-controls="editable-sample" name="name" value="{{$where['name']}}" type="text"></label>
                                                </div>
                                                <div class="col-lg-2" style="width:auto;">
                                                    <label>状态:
                                                        <select aria-controls="dynamic-table" class="form-control" style="width: auto"  name="status">
                                                            <option value="">请选择</option>
                                                            @foreach($status as $k=>$v)
                                                                <option value="{{$k}}" @if($k == $where['status']) selected @endif >{{$v['name']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </label>
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
                                        <th>关键词</th>
                                        <th>链接地址</th>
                                        <th>权重</th>
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
                                            <td class="center "> {{$v['url']}}</td>
                                            <td class=" ">
                                                {{$v['weight']}}
                                            </td>
                                            <td class=" ">
                                                <label for="" class="{{$status[$v->status]['class']}}">{{$status[$v->status]['name']}}</label>
                                            </td>
                                            <td class=" "><a class="delete" href="javascript:;">{{$v['create_at']}}</a></td>
                                            <td class="">
                                                <a href="{!! to_route('admin.keyword.edit',['id'=>$v['id']]) !!}">
                                                    <button class="btn btn-warning btn-xs" type="button"><i class="fa fa-pencil"></i> 修改</button>
                                                </a>
                                                @if($v->status == \App\Models\SysKeyword::STATUS_NORMAL)
                                                    <a href="javascript:del({{$v->id}},2)">
                                                        <button class="btn btn-default btn-xs" type="button"><i class="fa fa-ban"></i> 暂停</button>
                                                    </a>
                                                @endif
                                                @if($v->status == \App\Models\SysKeyword::STATUS_DISABLE)
                                                    <a href="javascript:del({{$v->id}},1)">
                                                        <button class="btn btn-success btn-xs" type="button"><i class="fa fa-check"></i> 启用</button>
                                                    </a>
                                                @endif
                                                <a href="javascript:del({{$v->id}},99)">
                                                    <button class="btn btn-danger btn-xs" type="button"><i class="fa fa-trash-o"></i> 删除</button>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-lg-12">
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