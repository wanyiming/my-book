@extends('admin.layouts.base')
@section("styles")
    <style>
        .form-inline .form-group{
            margin-left:20px;
        }
    </style>
@endsection
@section("scripts")
    <script>
        function editStatus (id,status) {
            if (parseInt(id)) {
                layer.confirm("是否确认修改信息状态?",{icon:3},function(){
                    layer.load(3);
                    $.post("{{to_route('admin.ad.status')}}",{id:id,status:status,_token:'{!! csrf_token() !!}'},function(data){
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
                layer.msg('请求参数错误，操作失败',{icon:2});
            }
        }
    </script>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <section class="panel">
                <header class="panel-heading">
                    <div class="clearfix">
                        广告位管理
                        <div class="btn-group pull-right">
                            <a role="button" class="btn btn-primary" href="{{to_route('admin.ad.add')}}">
                                添加广告位 <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>
                </header>

                <div class="panel-body">
                    <label class="pull-left">
                        信息总量：{{$lists->total()}}
                    </label>

                    <form class="form-inline pull-right">
                        <div class="form-group">
                            <label>子频道</label>
                            <select class="form-control" name="sub_channel">
                                <option value="">全部</option>
                                @foreach($subChannels as $key => $value)
                                    <option value="{{$key}}" @if($key == request('sub_channel')) selected @endif >{{$value}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>名称</label>
                            <input type="text" class="form-control" name="position_name" value="{{request('position_name')}}">
                        </div>

                        <div class="form-group">
                            <label>展示方式</label>
                            <select class="form-control" name="display_mode">
                                <option value="">全部</option>
                                @foreach($displayModes as $key => $value)
                                    <option value="{{$key}}" @if($key == request('display_mode')) selected @endif >{{$value}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>状态</label>
                            <select class="form-control" name="status">
                                <option value="">全部</option>
                                @foreach(['1'=>'启用','2'=>'禁用'] as $key => $value)
                                <option value="{{$key}}" @if($key == request('status')) selected @endif >{{$value}}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="form-group">
                            <button class="btn btn-search" type="submit"><i class="fa fa-search-minus"></i> 搜索</button>
                        </div>

                    </form>
                </div>

                <div class="panel-body">

                    <div class="adv-table">
                        
                        <table aria-describedby="editable-sample_info" class="table table-striped table-hover table-bordered dataTable">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>子频道</th>
                                <th>广告位名称</th>
                                <th>展示方式</th>
                                <th>宽度</th>
                                <th>高度</th>
                                <th>状态</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($lists))
                            @foreach($lists as $item)
                                <tr>
                                    <th>{{$item['id']}}</th>
                                    <th>{{$item['sub_channel_cn']}}</th>
                                    <th>{{$item['position_name']}}</th>
                                    <th>{{$item['display_mode_cn']}}</th>
                                    <th>{{$item['width']}}px</th>
                                    <th>{{$item['height']}}px</th>
                                    <th><label for="" class="{{$status[$item['status']]['class']}}">{{$status[$item['status']]['name']}}</label></th>
                                    <th>
                                        @if($item['status'] == \App\Models\AdPosition::STATUS_DISABLE)
                                            <a href="javascript:editStatus({{$item['id']}},{{\App\Models\AdPosition::STATUS_ENABLE}})">
                                                <button class="btn btn-success btn-xs" type="button"><i class="fa fa-check"></i> 启用</button>
                                            </a>
                                        @endif
                                        @if($item['status'] == \App\Models\AdPosition::STATUS_ENABLE)
                                            <a href="javascript:editStatus({{$item['id']}},{{\App\Models\AdPosition::STATUS_DISABLE}})">
                                                <button class="btn btn-default btn-xs" type="button"><i class="fa fa-ban"></i> 暂停</button>
                                            </a>
                                        @endif
                                        <a href="{{to_route('admin.ad.detail',['id'=>$item->id])}}">
                                            <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> 编辑广告</button>
                                        </a>
                                        <a href="{{to_route('admin.ad.edit',['id'=>$item->id])}}">
                                            <button class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> 修改</button>
                                        </a>
                                        @if($item['status'] != \App\Models\AdPosition::STATUS_DELETE)
                                                <a href="javascript:editStatus({{$item['id']}},{{\App\Models\AdPosition::STATUS_DELETE}})">
                                                    <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> 删除</button>
                                                </a>
                                        @endif
                                    </th>
                                </tr>
                            @endforeach
                            @endif
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="dataTables_info" id="editable-sample_info"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="dataTables_paginate paging_bootstrap pagination">
                                    {!! $lists->links('admin.layouts.page_html') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection