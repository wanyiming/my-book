@extends('admin.layouts.base')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    关键词编辑
                </header><br/>
                <div class="panel-body">
                    <form class="form-horizontal bucket-form" method="post" action="{{to_route('admin.keyword.save')}}">
                        <input type="hidden" name="id" value="@if(isset($info['id'])){{$info['id']}} @else 0 @endif ">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-sm-2 col-lg-2 control-label"><span class="text-danger">*</span>
                                权重
                                <br/>
                            </label>
                            <div class="col-lg-4">
                                <div class="col-sm-10">
                                    <input type="text" name="weight" class="form-control" required  placeholder="0" @if(isset($info['weight'])) value="{{$info['weight']}}" @else value="0" @endif ">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-lg-2 control-label"><span class="text-danger"></span>
                                状态
                                <br/>
                            </label>
                            <div class="col-lg-4">
                                <div class="col-sm-10">
                                    <input id="" name="status" @if(isset($info['status']) && $info['status'] == 1) checked @endif value="1" type="checkbox">
                                </div>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-sm-2 col-lg-2 control-label"><span class="text-danger">*</span>
                                关键词
                                <br/>
                            </label>
                            <div class="col-lg-4">
                                <div class="col-sm-10">
                                    <input type="text" name="name" class="form-control" required  placeholder="例: 关键词" @if(isset($info['name'])) value="{{$info['name']}}" @else value="" @endif ">
                                    @if ($errors->has('name'))
                                        <strong>{{ $errors->first('name') }}</strong>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('url') ? ' has-error' : '' }}">
                            <label class="col-sm-2 col-lg-2 control-label"><span class="text-danger">*</span>
                                链接地址
                                <br/>
                            </label>
                            <div class="col-lg-4">
                                <div class="col-sm-10">
                                    <input type="text" name="url" class="form-control" required  placeholder="例: http://www.baidu.com" @if(isset($info['url'])) value="{{$info['url']}}" @else value="" @endif ">
                                    @if ($errors->has('url'))
                                        <strong>{{ $errors->first('url') }}</strong>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-lg-2 control-label"></label>
                            <div class="col-lg-4"><button type="submit" class="btn btn-primary">保存</button></div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection