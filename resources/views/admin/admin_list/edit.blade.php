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
                    layer.msg(data['info'],{icon:1,time:100},function(){
                        window.location.href = "{{to_route('admin.admin_list.index')}}";
                    })
                }else{
                    layer.alert(data['info'],{icon:2});
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
                        职位列表
                    </header>
                    <div class="panel-body">
                        <form role="form" class="form-horizontal adminex-form" method="post" action="{{to_route('admin.admin_list.save')}}">
                            {!! csrf_field() !!}
                            <input type="hidden" name="id" value="{{$adminInfo['id']}}" />
                            {{--登录账号--}}
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">登录账号</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="name" value="{{$adminInfo['name']}}">
                                </div>
                            </div>
                            {{--工号--}}
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">工号</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="code" value="{{$adminInfo['code']}}">
                                </div>
                            </div>
                            {{--真实姓名--}}
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">真实姓名</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="user_name" value="{{$adminInfo['user_name']}}">
                                </div>
                            </div>
                            {{--姓名全拼--}}
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">姓名全拼</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="user_pin" value="{{$adminInfo['user_pin']}}">
                                </div>
                            </div>
                            {{--电话号码--}}
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">电话号码</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="phone" value="{{$adminInfo['phone']}}">
                                </div>
                            </div>
                            <div class="form-group">
                                {{--权限组--}}
                                <label class="col-sm-2 control-label">角色组</label>
                                <div class="col-sm-8 form-inline">
                                    @foreach($roles as $role)
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="role_ids[]" value="{{$role['id']}}" @if(in_array($role['id'],(array)$adminInfo['role_ids'])) checked @endif>{{$role['name'] ?? ''}}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            {{--排序--}}
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">排序</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="sort" value="{{$adminInfo['sort']}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    <button class="btn btn-info submit_btn" type="button">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        提交
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