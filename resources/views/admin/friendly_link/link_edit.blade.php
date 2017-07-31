@extends('admin.layouts.base')
@section('scripts')

    <script src="/static/ueditor/ueditor.config.js"></script>
    <script src="/static/ueditor/ueditor.all.js"></script>
    <script src="/static/ueditor/lyr.config.js"></script>
    <script src="/assets/dist/distpicker.min.js"></script>
    <script src="/assets/js/a_public.js"></script>
    <script>
        setUeEditorObject('container','{{ csrf_token() }}');
    </script>
    <script>
        $(function () {
            $(".btn-primary").click(function () {
                _fromSubmit($(".adminex-form"), 'post');
            })
        })
        $('.btn-primary').click(function(){
            $('.adminex-form').serialize(),function(data){
                if (data.status == -1){
                    layer.alert(data.msg,{
                        skin:'layui-layer-lan',closeBtn:0,shift:4 //动画类型
                    });
                }
            };
        })
    </script>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" method="post" action="{{to_route('admin.friendly_link.save')}}">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{$link->id}}">
                        <div class="form-group {{ $errors->has('attr_title') ? ' has-error' : '' }}">
                            <label class="col-sm-2 col-lg-2 control-label"><span class="text-danger">*</span> 网站名称</label>
                            <div class="col-lg-8">
                                    <input title="" name="website" type="text" class="form-control" value="{{$link->website}}" required >
                                <span class="help-block">例如： title=""</span>
                                <span class="help-block">{{ $errors->first('attr_title') }}</span>
                            </div>
                       </div>
                        <div class="form-group {{ $errors->has('attr_title') ? ' has-error' : '' }}">
                            <label class="col-sm-2 col-lg-2 control-label"><span class="text-danger">*</span> 网址</label>
                            <div class="col-lg-8">
                                <input title="" name="weburl" type="text" class="form-control" value="{{$link->weburl}}" required >
                                <span class="help-block">例如： title=""</span>
                                <span class="help-block">{{ $errors->first('attr_title') }}</span>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('attr_title') ? ' has-error' : '' }}">
                            <label class="col-sm-2 col-lg-2 control-label"><span class="text-danger">*</span> 电子邮箱</label>
                            <div class="col-lg-8">
                                <input title="" name="email" type="text" class="form-control" value="{{$link->email}}" required >
                                <span class="help-block">例如： title=""</span>
                                <span class="help-block">{{ $errors->first('attr_title') }}</span>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('attr_title') ? ' has-error' : '' }}">
                            <label class="col-sm-2 col-lg-2 control-label"><span class="text-danger">*</span> 排序值</label>
                            <div class="col-lg-8">
                                <input title="" name="sort" type="text" class="form-control" value="{{$link->sort}}" required >
                                <span class="help-block">例如： title=""</span>
                                <span class="help-block">{{ $errors->first('attr_title') }}</span>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('attr_title') ? ' has-error' : '' }}">
                            <label class="col-sm-2 col-lg-2 control-label"><span class="text-danger">*</span> 网站介绍</label>
                            <div class="col-lg-8">
                                <textarea class="form-control" rows="3" name="content">{{$link->content}}</textarea>
                                <span class="help-block">例如： title=""</span>
                                <span class="help-block">{{ $errors->first('attr_title') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-lg-2 control-label"></label>
                            <div class="col-lg-4"><button type="button" class="btn btn-primary">保存</button></div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection