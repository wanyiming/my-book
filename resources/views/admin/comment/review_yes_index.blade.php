@extends('admin.layouts.base')
@section('styles')
@endsection
@section('scripts')
    <script src="/assets/laydate/laydate.js"></script>
    <script>
        $(function () {
            $(".operation").click(function () {
                var dataId = $(this).attr('data-id');
                var status = $(this).attr('data-status');
                layer.confirm("确认操作？",{icon:3},function () {
                    $.post("{!! to_route('admin.comment.edit_status') !!}", {id:dataId,status:status,_token:"{!! csrf_token() !!}"},function (result) {
                        if (result.status > 0) {
                            layer.msg(result.msg, {icon:1});
                            location.reload(); return false;
                        }
                        layer.msg(result.msg, {icon:2});return false;
                    })
                })
            })
        })
    </script>
@endsection
@section('content')
    <div class="wrapper">
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        <br/> 备注：如果内容中出现红色字体表示为敏感词
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                         </span>
                    </header>
                    <div class="panel-body">
                        <div class="adv-table editable-table ">
                            <div class="clearfix">
                                <div class="btn-group">
                                    <a href="javascript:;">
                                        <button id="editable-sample_new" class="btn btn-primary">
                                            评价列表
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
                                                    <label>状态:
                                                        <select aria-controls="dynamic-table" class="form-control" style="width: auto"  name="status">
                                                            <option value="">请选择</option>
                                                            @foreach($status as $k=>$v)
                                                                <option value="{{$k}}" @if($k == $where['status']) selected @endif>{{$v['name']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </label>
                                                </div>
                                                <div class="col-md-4" style="width: auto">
                                                    <label>评价时间:
                                                        <input class="form-control dpd1" onclick="laydate({  format: 'YYYY/MM/DD'})" value="{{isset($where['begin_time']) ? $where['begin_time'] : ''}}" name="begin_time" type="text">
                                                        <input class="form-control dpd1" onclick="laydate({  format: 'YYYY/MM/DD'})" value="{{isset($where['end_time']) ? $where['end_time'] : ''}}" name="end_time" type="text">
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
                                        <th>评价用户</th>
                                        <th>评价内容</th>
                                        <th>评价书本</th>
                                        <th>当前状态</th>
                                        <th>评价时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody aria-relevant="all" aria-live="polite" role="alert">
                                    @foreach($data as $k=>$v)
                                        <tr class="odd">
                                            <td class="">{{$v['id']}}</td>
                                            <td class=""> {{$v['name']}}</td>
                                            <td class=""> {{$v['content']}}</td>
                                            <td class=""> {{$v['book_title']}}</td>
                                            <td class=""><label class="{{$status[$v['status']]['class']}}">{{$status[$v['status']]['name']}}</label></td>
                                            <td class=""><a class="delete" href="javascript:;">{{$v['create_time']}}</a></td>
                                            <td>
                                                @if($v['status'] == \App\Models\Comment::STATUS_NO)
                                                    <a class="ajax-restore operation" data-status="{{\App\Models\Comment::STATUS_NORMAL}}" data-id="{{$v['id']}}" data-status="1"  href="javascript:;">
                                                        <button class="btn btn-success btn-xs xiajia"><i class="fa fa-check"></i> 启用</button>
                                                    </a>
                                                @elseif($v['status'] == \App\Models\Comment::STATUS_NORMAL)
                                                    <a class="ajax-restore operation"  data-status="{{\App\Models\Comment::STATUS_NO}}" data-id="{{$v['id']}}" data-status="2"  href="javascript:;">
                                                        <button class="btn btn-default btn-xs shangjia"><i class="fa fa-ban"></i> 停用</button>
                                                    </a>
                                                @endif
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
@endsection      