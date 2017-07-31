@extends('admin.layouts.base')
@section('styles')
@endsection
@section('scripts')
    <script>
        {{--节点添加修改--}}
        $('.row').on('click','.submit_btn',function(){
            var tourl = $(this).parents('form').attr('action');
            $.post(tourl,$(this).parents('form').serialize(),function(data){
                if(data['status'] > 0){
                    layer.alert(data['msg'],{icon:1},function(){
                        window.location.href = "{{to_route('admin.public.logout')}}";
                    })
                }else{
                    layer.alert(data['msg'],{icon:2});
                }
            },'json').error(function(){
                layer.alert('请求失败',{icon:2});
            })
        })
    </script>
@endsection

@section('content')
    <!--body wrapper start-->
    {{--内容页开始--}}
    <div class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        密码修改
                    </header>
                    <div class="panel-body">
                        <form role="form" class="form-horizontal adminex-form" method="post" action="{{to_route('admin.access.password_save')}}">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">旧密码</label>
                                <div class="col-sm-5">
                                    <input type="password" class="form-control" maxlength="16" name="old_pass">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">新密码</label>
                                <div class="col-sm-5">
                                    <input type="password" class="form-control" maxlength="16" name="password">
                                    <strong for="" class="" style="color: red">*</strong>账号密码不能是弱密码;建议密码由数组+字母+特殊字符（8-16位）组成；
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">确认密码</label>
                                <div class="col-sm-5">
                                    <input type="password" class="form-control"  maxlength="16" name="password_confirmation">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    <button class="btn btn-info submit_btn" type="button">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        提交
                                    </button>
                                    &nbsp; &nbsp; &nbsp;
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
    {{--内容页结束--}}
@endsection