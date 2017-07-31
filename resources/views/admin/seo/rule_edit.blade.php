@extends('admin.layouts.base')
@section('styles')
    <!--ios7-->
    <link rel="stylesheet" type="text/css" href="/assets/js/ios-switch/switchery.css"/>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">SEO规则编辑</header>
                <div class="panel-body">

                    <form class="form-horizontal adminex-form" method="post"
                          action="{{ to_route('admin.seo.save') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{$id ?? 0}}">
                        @if(empty($id))
                            <div class="form-group {{ $errors->has('call_key') ? ' has-error' : '' }}">
                                <label class="col-sm-2 col-lg-2 control-label"><span class="text-danger">*</span>调用key</label>
                                <div class="col-lg-4">
                                    <div class="m-bot15">
                                        <input title="" name="call_key" type="text" class="form-control"
                                               value="{{ old('call_key') ?? $call_key ?? ''}}" required autofocus>
                                    </div>
                                    <span class="help-block">{{ $errors->first('call_key') }}</span>
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="call_key" value="{{$call_key ?? ''}}">
                        @endif


                        <div class="form-group {{ $errors->has('page_name') ? ' has-error' : '' }}">
                            <label class="col-sm-2 col-lg-2 control-label">名称</label>
                            <div class="col-lg-4">
                                <div class=" m-bot15">
                                    <input title="" name="page_name" type="text" class="form-control"
                                           value="{{ old('page_name') ?? $page_name ?? ''}}" required autofocus>
                                </div>
                                <span class="help-block">{{ $errors->first('page_name') }}</span>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
                            <label class="col-sm-2 col-lg-2 control-label"><span class="text-danger">*</span>Title</label>
                            <div class="col-lg-4">
                                <div class="m-bot15">
                                    <input title="" name="title" type="text" class="form-control"
                                           value="{{ old('title') ?? $title ?? ''}}" required autofocus>
                                </div>
                                <span class="help-block">{{ $errors->first('title') }}</span>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('keywords') ? ' has-error' : '' }}">
                            <label class="col-sm-2 col-lg-2 control-label"><span class="text-danger">*</span>Keywords</label>
                            <div class="col-lg-4">
                                <div class="m-bot15">
                                    <input title="" name="keywords" type="text" class="form-control"
                                           value="{{ old('keywords') ?? $keywords ?? ''}}" required autofocus>
                                </div>
                                <span class="help-block">{{ $errors->first('keywords') }}</span>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
                            <label class="col-sm-2 col-lg-2 control-label"><span class="text-danger">*</span>Description</label>
                            <div class="col-lg-4">
                                <div class="m-bot15">
                                    <input title="" name="description" type="text" class="form-control"
                                           value="{{ old('description') ?? $description ?? ''}}" required autofocus>
                                </div>
                                <span class="help-block">{{ $errors->first('description') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-lg-2 control-label"></label>
                            <div class="col-lg-4">
                                <button type="submit" class="btn btn-danger">保存</button>
                            </div>

                        </div>

                    </form>

                </div>
            </section>
        </div>
    </div>
@endsection
@section('scripts')
    <!--ios7-->
    <script src="/assets/js/ios-switch/switchery.js"></script>
    <script src="/assets/js/ios-switch/ios-init.js"></script>

@endsection