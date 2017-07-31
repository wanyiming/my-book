@extends('admin.layouts.base')
@section('styles')
    <!--ios7-->
    <link rel="stylesheet" type="text/css" href="/assets/js/ios-switch/switchery.css"/>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">添加广告位</header>
                <div class="panel-body">

                    <form class="form-horizontal adminex-form" method="post"
                          action="{{ to_route('admin.ad.save') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{$id ?? 0}}">
                        @if(empty($id))
                            <div class="form-group {{ $errors->has('call_key') ? ' has-error' : '' }}">
                                <label class="col-sm-2 col-lg-2 control-label"><span
                                            class="text-danger">*</span>调用key</label>
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
                            <input type="hidden" name="station" value="{{$station ?? ''}}">
                        @endif

                        <div class="form-group {{ $errors->has('sub_channel') ? ' has-error' : '' }}">
                            <label class="col-sm-2 col-lg-2 control-label"><span class="text-danger">*</span>子频道</label>
                            <div class="col-lg-4">
                                <div class="m-bot15">
                                    <select name="sub_channel" class="form-control input-sm m-bot15" title="">
                                        @if(isset($subChannels))
                                            @foreach($subChannels as $_channelKey => $_channelName)
                                                <option value="{{ $_channelKey }}"
                                                        @if(isset($sub_channel) && $_channelKey == $sub_channel) selected @endif>{{$_channelName}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <span class="help-block">{{ $errors->first('sub_channel') }}</span>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('position_name') ? ' has-error' : '' }}">
                            <label class="col-sm-2 col-lg-2 control-label"><span
                                        class="text-danger">*</span>广告位名称</label>
                            <div class="col-lg-4">
                                <div class="m-bot15">
                                    <input title="" name="position_name" type="text" class="form-control"
                                           value="{{ old('position_name') ?? $position_name ?? ''}}" required autofocus>
                                </div>
                                <span class="help-block">{{ $errors->first('position_name') }}</span>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('display_mode') ? ' has-error' : '' }}">
                            <label class="col-sm-2 col-lg-2 control-label"><span class="text-danger">*</span>展现方式</label>
                            <div class="col-lg-4">
                                <div class="m-bot15">
                                    <select name="display_mode" class="form-control input-sm m-bot15" title="">
                                        @if(isset($displayModes))
                                            @foreach($displayModes as $_modeKey => $_modeName)
                                                <option value="{{ $_modeKey }}"
                                                        @if(isset($display_mode) && $_modeKey == $display_mode) selected @endif>{{$_modeName}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <span class="help-block">{{ $errors->first('display_mode') }}</span>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('width') ? ' has-error' : '' }}">
                            <label class="col-sm-2 col-lg-2 control-label"><span
                                        class="text-danger">*</span>图片宽度</label>
                            <div class="col-lg-4">
                                <div class="m-bot15">
                                    <input title="" name="width" type="text" class="form-control"
                                           value="{{ old('width') ?? $width ?? ''}}" required autofocus>
                                </div>
                                <span class="help-block">{{ $errors->first('width') }}</span>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('height') ? ' has-error' : '' }}">
                            <label class="col-sm-2 col-lg-2 control-label"><span
                                        class="text-danger">*</span>图片高度</label>
                            <div class="col-lg-4">
                                <div class="m-bot15">
                                    <input title="" name="height" type="text" class="form-control"
                                           value="{{ old('height') ?? $height ?? ''}}" required autofocus>
                                </div>
                                <span class="help-block">{{ $errors->first('height') }}</span>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('speed') ? ' has-error' : '' }}">
                            <label class="col-sm-2 col-lg-2 control-label">速度</label>
                            <div class="col-lg-4">
                                <div class="m-bot15">
                                    <input title="" name="speed" type="text" class="form-control"
                                           value="{{ old('speed') ?? $speed ?? '500'}}" required autofocus>
                                </div>
                                <span class="help-block">(提示:速度单位毫秒,1秒=1000毫秒)</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-lg-2 control-label">启用</label>
                            <div class="col-lg-4">
                                <div class="m-bot15">
                                    <div class="slide-toggle">
                                        <input name="status" title="" value="1" type="checkbox"
                                               class="js-switch"
                                               @if(empty(old('status')))
                                               @if(isset($status) && 1 == $status) checked @endif
                                               @else
                                               @if(1 == old('status')) checked @endif
                                                @endif
                                        />
                                    </div>
                                </div>
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