@extends('admin.layouts.base')
@section('styles')
@endsection
@section('content')
<div class="wrapper">
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
                            <label class="col-lg-3 col-sm-3 control-label">简介：</label>
                            <div class="col-lg-9">
                                <div class="iconic-input right">
                                    {{$bookinfo['profiles']}}
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
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    添加/编辑章节信息
                </header>
                <div class="panel-body">
                    <form role="form" class="form-horizontal adminex-form" method="post" onsubmit=" return saveBook();" action="{{route('admin.chapter.save')}}">
                        {!! csrf_field() !!}
                        <input type="hidden" name="id" value="{{$info['id'] ?? 0}}" />
                        <input type="hidden" name="book_uuid" value="{{$bookinfo['uuid']}}">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">*标题：</label>
                            <div class="col-sm-3">
                                <input maxlength="30" type="text" class="form-control" name="title" value="{{$info['title'] ?? ''}}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">阅读量：</label>
                            <div class="col-sm-3">
                                <input maxlength="30" type="text" class="form-control" name="reading_num" value="{{$info['reading_num'] ?? ''}}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">字数：</label>
                            <div class="col-sm-3">
                                <div class="iconic-input right">
                                    <input type="text" class="form-control" name="reading_num" value="{{$info['reading_num']}}" placeholder="right icon">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">链接地址：</label>
                            <div class="col-sm-4">
                                <input maxlength="30" type="text" class="form-control" name="url" value="{{$info['url'] ?? ''}}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">书名简介：</label>
                            <div class="col-sm-10">
                                <textarea  id="container" name="content">{!! $info['content'] ?? '' !!}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button class="btn btn-info submit_btn">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    提交
                                </button>
                                <button class="btn" type="reset">
                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                    重置
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script src="/static/ueditor/ueditor.config.js"></script>
    <script src="/static/ueditor/ueditor.all.js"></script>
    <script src="/static/ueditor/lyr.config.js"></script>
    <script>
        setUeEditorObject('container','{{ csrf_token() }}');
        function  saveBook() {
            _fromSubmit($(".adminex-form"), 'post');
            return false;
        }
    </script>
@endsection