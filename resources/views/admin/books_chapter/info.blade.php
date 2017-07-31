@extends('admin.layouts.base')
@section('content')
<div class="row">
        <div class="col-lg-6">
            <section class="panel">
                <header class="panel-heading">
                    书本信息
                </header>
                <div class="panel-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="col-lg-3 col-sm-3 control-label">书名：</label>
                            <div class="col-lg-9">
                                <div class="iconic-input right">
                                    <input type="text" class="form-control" value="{{$bookinfo['title']}}" placeholder="right icon">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 col-sm-3 control-label">作者：</label>
                            <div class="col-lg-9">
                                <div class="iconic-input right">
                                    <input type="text" class="form-control" value="{{$bookinfo['author']}}" placeholder="right icon">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 col-sm-3 control-label">分类：</label>
                            <div class="col-lg-9">
                                <div class="iconic-input right">
                                    <input type="text" class="form-control" value="{!! (new \App\Models\BookType())->getTypeName($bookinfo['book_type']) !!}" placeholder="right icon">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 col-sm-3 control-label">简介：</label>
                            <div class="col-lg-9">
                                <div class="iconic-input right">
                                    {{$bookinfo['profiles']}}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 col-sm-3 control-label">总的章节数：</label>
                            <div class="col-lg-9">
                                <div class="iconic-input right">
                                    <label for="" class="label label-success">{{$chapter->count()}}</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
        <div class="col-lg-6">
            <section class="panel">
                <header class="panel-heading">
                    扩展信息
                </header>
                <div class="panel-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="col-lg-3 col-sm-3 control-label">封　　面：</label>
                            <div class="col-lg-9">
                                <div class="iconic-input right">
                                    <img src="{{$bookinfo['book_cover']}}" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 col-sm-3 control-label">书本状态：</label>
                            <div class="col-lg-9">
                                <div class="iconic-input right">
                                    <label for="" class="label label-success">
                                        {{$bookinfo['type_id'] == '1' ? '全本' : '连载'}}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 col-sm-3 control-label">最新章节：</label>
                            <div class="col-lg-9">
                                <div class="iconic-input">
                                    <input type="text" class="form-control" value="{{$bookinfo['update_fild']}}" placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 col-sm-3 control-label">更新时间：</label>
                            <div class="col-lg-9">
                                <div class="iconic-input right">
                                    <input type="text" class="form-control" value="{{$bookinfo['update_time']}}"  placeholder="left ">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <div class="panel-body">
                <div class="adv-table" >
                    <table aria-describedby="editable-sample_info" class="table table-striped table-hover table-bordered dataTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>章节名称</th>
                                <th>添加时间</th>
                                <th>阅读量</th>
                                <th>链接地址</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(isset($chapter))
                            @foreach($chapter as $item)
                                <tr>
                                    <th>{{$item['id']}}</th>
                                    <th><a href="" title="查看:{{$item['title']}}内容">{!! $item['title'] !!}</a></th>
                                    <th>{{$item['create_time']}}</th>
                                    <th>{{$item['reading_num']}}</th>
                                    <th>{{$item['url']}}</th>
                                    <th>
                                        <a href="{{to_route('admin.books.edit',['id' => $item['id']])}}">
                                            <button class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> 编辑</button>
                                        </a>
                                        <a class="ajax-restore operation" data-id="{{$item['id']}}" data-status="99" href="javascript:;">
                                            <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> 删除</button>
                                        </a>
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
                                {!! $chapter->links('admin.layouts.page_html') !!}
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