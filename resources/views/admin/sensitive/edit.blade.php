@extends('admin.layouts.base')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    编辑敏感词
                </header><br/>
                <div class="panel-body">
                    <form class="form-horizontal bucket-form" method="post" action="{{to_route('admin.sensitive.save')}}">
                        <input type="hidden" name="id" value="@if(isset($info['id'])){{$info['id']}} @else 0 @endif ">
                        {{ csrf_field() }}
                        <div class="form-group {{ $errors->has('sensitive_name') ? ' has-error' : '' }}">
                            <label class="col-sm-2 col-lg-2 control-label"><span class="text-danger">*</span>
                                敏感词
                                <br/>
                            </label>
                            <div class="col-lg-4">
                                <div class="col-sm-10">
                                    <input type="text" name="sensitive_name" class="form-control" required  placeholder="例: 敏感词" value="@if(isset($info['name'])){{$info['name']}}@endif ">
                                    @if ($errors->has('sensitive_name'))
                                        <strong>{{ $errors->first('sensitive_name') }}</strong>
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