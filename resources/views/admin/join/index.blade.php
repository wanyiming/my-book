@extends('admin.layouts.base')
@section('scripts')
    <script>
        $('.change-btn').click(function () {
            var _type = $(this).attr('data-type');
            var _id = $(this).closest('tr').attr('data-id');
            var _url = "{{to_route('admin.join.change.status')}}";
            $.post(_url,{id:_id,type:_type,_token:"{{csrf_token()}}"},function (data) {
                if(data['status'] == 1){
                    layer.alert(data['msg'],{icon:1},function () {
                        window.location.reload();
                    })
                }else{
                    layer.msg(data['msg'],{icon:2});
                }
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
                        加盟留言
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                         </span>
                    </header>
                    <div class="panel-body">
                        <div class="adv-table ">
                            <div class="clearfix">
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
                                    <div>
                                        <div class="">
                                            <div id="editable-sample_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                                    <div class="row">
                                                        <div class="dataTables_length" id="editable-sample_length" style="min-height: 70px;">
                                                            <div class="col-lg-2">
                                                                <label for="" style="line-height: 30px"> <strong>信息总量：{{$list->total()}}</strong></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <table aria-describedby="editable-sample_info" class="table table-striped table-hover table-bordered dataTable" id="editable-sample">
                                    <thead>
                                    <tr role="row">
                                        <th>ID</th>
                                        <th>类型</th>
                                        <th>联系人</th>
                                        <th>职位</th>
                                        <th>联系电话</th>
                                        <th>说明</th>
                                        <th>地址</th>
                                        <th>状态</th>
                                        <th>添加时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody aria-relevant="all" aria-live="polite" role="alert">
                                        @foreach($list as $value)
                                        <tr class="odd" data-id="{{$value->id}}">
                                            <td class="  sorting_1">{{$value->id}}</td>
                                            <td class=" "> {{$type_group[$value->type]}}</td>
                                            <td class=" "> {{$value->name}}</td>
                                            <td class=" "> {{$value->title}}</td>
                                            <td class=" "> {{$value->mobile}}</td>
                                            <td class=" "> {{$value->selling_product}}</td>
                                            <td class=" ">
                                                {{$value->address_str}}
                                            </td>
                                            <td class=" "> {{$value->status_cn}}</td>
                                            <td class=" ">{{$value->created_at}}</td>
                                            <td class=" ">
                                                @if($value->status == 1)
                                                <a href="javascript:;" class="btn btn-info btn-xs change-btn" data-type="2"><i class="fa fa-eye"></i> 标记为已读</a>
                                                <a href="javascript:;" class="btn btn-danger btn-xs change-btn" data-type="99"><i class="fa fa-trash-o"></i> 删除</a>
                                                    @elseif($value->status == 2)
                                                    <a href="javascript:;" class="btn btn-danger btn-xs change-btn" data-type="99"><i class="fa fa-trash-o"></i> 删除</a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{--分页html开始--}}
                                @if($list->lastPage() > 1)
                                    <div class="row-fluid">
                                        <div class="span6">
                                            <div class="dataTables_paginate paging_bootstrap pagination">
                                                {{ $list->appends($get)->links('admin.layouts.page_html') }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                {{--分页html结束--}}
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>


@endsection
