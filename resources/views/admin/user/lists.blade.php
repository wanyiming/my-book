@extends('admin.layouts.base')
@section('styles')
    <style type="text/css">
        .adv-table .dataTables_length select {
            width: 120px;
    </style>
@endsection
@section('scripts')
    <script>
        $('.upgrade').click(function(){
            var userUuid = $(this).attr('data-id');
            if (!userUuid) {
                layer.msg("请求异常，参数错误",{icon:2});return false;
            }
            layer.confirm('是都确认对用户数据进行操作？',{icon:3}, function(){
                $.post('{!! to_route('admin.user.chang_status') !!}',{'_token':'{!! csrf_token() !!}','uuid':userUuid},function(result){
                    if (result.status == 1){
                        location.reload();
                    }else {
                        layer.msg(result.msg,{icon:2});return false;
                    }
                },'json').error(function () {
                    layer.msg("请求异常",{icon:2});
                });
            });
        })
    </script>
@endsection
@section('content')
    <div class="wrapper">
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                     用户列表
                        <span class="tools pull-right">
                            <a href="javascript:void(0);" class="fa fa-chevron-down"></a>
                            <a href="javascript:void(0);" class="fa fa-times"></a>
                        </span>
                    </header>
                    <div class="panel-body">
                        <div class="adv-table ">
                            <div class="clearfix">
                                <div class="btn-group">
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
                                                <label for="" style="line-height: 30px"> <strong>信息总量：{{$lists->total()}}</strong></label>
                                            </div>
                                            <div style="float: right; padding-right: 15px;">
                                                <div class="col-lg-2" style="width:auto;">
                                                    <label>登录名: <input class="form-control" maxlength="20" aria-controls="editable-sample" name="name" value="{{$where['name'] ?? ''}}" type="text"></label>
                                                </div>
                                                <div class="col-lg-2" style="width:auto;">
                                                    <label>昵称: <input class="form-control" maxlength="20" aria-controls="editable-sample" name="alias_name" value="{{$where['alias_name'] ?? ''}}" type="text"></label>
                                                </div>
                                                <div class="col-lg-2" style="width:auto;">
                                                    <label>性别: </label>
                                                    <select class="form-control" name="sex">
                                                        <option value=""> 全部类型 </option>
                                                        @foreach($sex_ as $keySex => $valueSex)
                                                            <option value="{{$keySex}}" @if(request('sex') == $keySex) selected @endif>{{$valueSex['name']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-2" style="width:auto;">
                                                    <label>状态: </label>
                                                    <select class="form-control" name="status">
                                                        <option value=""> 全部类型 </option>
                                                        @foreach($status_ as $keyStatus => $valueStatus)
                                                            <option value="{{$keyStatus}}" @if(request('status') == $keyStatus) selected @endif>{{$valueStatus['name']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <button class="btn btn-search" type="submit"><i class="fa fa-search-minus"></i>搜索</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <table aria-describedby="editable-sample_info" class="table table-striped table-hover table-bordered dataTable" id="editable-sample">
                                    <thead>
                                    <tr role="row">
                                        <th>ID</th>
                                        <th>登录名</th>
                                        <th>昵称</th>
                                        <th>积分</th>
                                        <th>经验值</th>
                                        <th>头像</th>
                                        <th>邮箱</th>
                                        <th>性别</th>
                                        <th>状态</th>
                                        <th>注册时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody aria-relevant="all" aria-live="polite" role="alert">
                                    @foreach($lists as $key=>$value)
                                        <tr class="odd">
                                            <td class="  sorting_1">{{ $value->id }}</td>
                                            <td class=" "> {{ $value->name }}</td>
                                            <td class=" ">{{ $value->alias_name }}</td>
                                            <td class=" ">{{ $value->integral }}</td>
                                            <td class=" ">{{ $value->experience }}</td>
                                            <td class=" "><img src="{{$value->avatar}}" width="30px" height="40px" alt=""></td>
                                            <td class=" ">{{ $value->email }}</td>
                                            <th><label for="" class="label {{$sex_[$value->sex]['class']}}">{{$sex_[$value->sex]['name']}}</label></th>
                                            <th><label for="" class="label {{$status_[$value->status]['class']}}">{{$status_[$value->status]['name']}}</label></th>
                                            <td class=" ">{{ $value->register_time }}</td>
                                            <td class=" ">
                                                @if ($value->status == 1)
                                                    <button class="btn btn-default btn-xs upgrade" data-id="{{$value->uuid}}"><i class="fa fa-ban"></i> 禁用</button>
                                                @elseif($value->status == 2)
                                                    <button class="btn btn-success btn-xs upgrade" data-id="{{$value->uuid}}"><i class="fa fa-check"></i> 启用</button>
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
                                            {!! $lists->appends($where)->links('admin.layouts.page_html') !!}
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
