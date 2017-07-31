@extends('admin.layouts.base')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading">
                <div class="clearfix">
                    书本列表信息
                    <div class="btn-group pull-right">
                        <a role="button" class="btn btn-primary" href="{{to_route('admin.books.create')}}">
                            添加书本 <i class="fa fa-plus"></i>
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
                        <label>书本分类 </label>
                        <select class="form-control" name="book_type">
                            <option value="">全部分类</option>
                            @foreach($types as $keyType => $valueType)
                                <option value="{{$keyType}}" @if(request('book_type') == $keyType) selected @endif>{{$valueType}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>状态 </label>
                        <select class="form-control" name="status">
                            <option value=""> 全部状态 </option>
                            @foreach($status_ as $keyStatus => $valueStatus)
                                <option value="{{$keyStatus}}" @if(request('status') == $keyStatus) selected @endif>{{$valueStatus['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>书本类型 </label>
                        <select class="form-control" name="type_id">
                            <option value=""> 全部类型 </option>
                            @foreach($type_ as $keyType => $valueType)
                                <option value="{{$keyType}}" @if(request('type_id') == $keyType) selected @endif>{{$valueType['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>书名 </label>
                        <input type="text" class="form-control" name="title" value="{{request('title')}}">
                    </div>
                    <div class="form-group">
                        <label>作者 </label>
                        <input type="text" class="form-control" name="author" value="{{request('author')}}">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-search" type="submit"><i class="fa fa-search-minus"></i> 搜索</button>
                    </div>
                </form>
            </div>
            <div class="panel-body">
                <div class="adv-table" >
                    <table aria-describedby="editable-sample_info" class="table table-striped table-hover table-bordered dataTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>书名</th>
                                <th>封面</th>
                                <th>作者</th>
                                <th>字数</th>
                                <th>所属分类</th>
                                <th>最新章节</th>
                                <th>添加时间</th>
                                <th>阅读量</th>
                                <th>推荐的票</th>
                                <th>是否推荐</th>
                                <th>类型</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(isset($lists))
                            @foreach($lists as $item)
                                <tr>
                                    <th>{{$item['id']}}</th>
                                    <th><a title="管理：{!! $item['title'] !!} 内容" href="{{to_route('admin.books.chapter',['uuid' => $item['uuid']])}}">{!! $item['title'] !!}</a></th>
                                    <th><img src="{{$item['book_cover']}}" alt="" width="30px" height="40px"></th>
                                    <th>{{$item['author']}}</th>
                                    <th>{{$item['font_size']}}</th>
                                    <th>{{$types[$item['book_type']]}}</th>
                                    <th>
                                        {!! html_entity_decode($item['update_fild']) !!}<br>跟新时间:{{$item['update_time']}}
                                    </th>
                                    <th>{{$item['create_time']}}</th>
                                    <th>{{$item['read_num']}}</th>
                                    <th>{{$item['recom_num']}}</th>
                                    <th>{!! recommend_info(\App\Models\Recommend::OBJECT_TYPE_BOOK, $item['id']) !!}</th>
                                    <th><label for="" class="label {{$type_[$item['type_id']]['class']}}">{{$type_[$item['type_id']]['name']}}</label></th>
                                    <th><label for="" class="label {{$status_[$item['status']]['class']}}">{{$status_[$item['status']]['name']}}</label></th>
                                    <th>
                                        <a href="{{to_route('admin.books.edit',['id' => $item['id']])}}">
                                            <button class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> 编辑</button>
                                        </a>
                                        <a class="ajax-restore operation" data-id="{{$item['id']}}" data-status="99" href="javascript:;">
                                            <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> 删除</button>
                                        </a>
                                        @if($item['status'] == \App\Models\Books::STATUS_ON)
                                            <a class="ajax-restore operation" data-id="{{$item['id']}}" data-status="2"  href="javascript:;">
                                                <button class="btn btn-default btn-xs shangjia"><i class="fa fa-ban"></i> 停用</button>
                                            </a>
                                            @elseif($item['status'] == \App\Models\Books::STATUS_OFF)
                                            <a class="ajax-restore operation" data-id="{{$item['id']}}" data-status="1"  href="javascript:;">
                                                <button class="btn btn-success btn-xs xiajia"><i class="fa fa-check"></i> 启用</button>
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
@section('scripts')
    <script>
        $(function(){
            $(".ajax-restore").click(function(){
                var _this = $(this);
                layer.confirm("是否确认操作?",{icon:3}, function () {
                    $.post("{{to_route('admin.books.operation')}}",{'id':_this.attr('data-id'),'status':_this.attr('data-status'),"_token":"{{csrf_token()}}"},function (result) {
                        if(result.status == 1){
                            window.location.reload();
                        }else{
                            layer.msg(result.msg,{icon:2});
                        }
                    }),error(function () {
                        layer.msg("请求异常",{icon:2});
                    });
                });
                return false;
            });
        });
    </script>
@endsection