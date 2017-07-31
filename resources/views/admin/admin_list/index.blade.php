@extends('admin.layouts.base')
@section('styles')
<!--dynamic table-->
<link href="/assets/js/advanced-datatable/css/demo_page.css" rel="stylesheet" />
<link href="/assets/js/advanced-datatable/css/demo_table.css" rel="stylesheet" />
<link rel="stylesheet" href="/assets/js/data-tables/DT_bootstrap.css" />
@endsection
@section('scripts')
<script>
    {{--节点删除--}}
    $('.row').on('click','.del_btn',function(){
        var tourl = "{{to_route('admin.admin_list.delete')}}";
        var _this = $(this);
        var _id = _this.parents('tr').attr('data-id');
        layer.confirm('确定删除该账号？删除后无法恢复！',function () {
            $.post(tourl,{id:_id,_token:"{!! csrf_token() !!}"},function(data){
                if(data['status'] > 0){
                    layer.msg(data['info'],{icon:1,time:100},function(){
                        window.location.reload();
                    })
                }else{
                    layer.alert(data['info'],{icon:2});
                }
            },'json').error(function(){
                layer.alert('请求失败',{icon:2});
            });
        });

    });
    {{--锁定、解锁--}}
    $('.row').on('click','.lock_btn',function(){
        var tourl = "{{to_route('admin.admin_list.save_status')}}";
        var _this = $(this);
        var _id = _this.parents('tr').attr('data-id');
            $.post(tourl,{id:_id,_token:"{!! csrf_token() !!}"},function(data){
                if(data['status'] > 0){
                    layer.msg(data['info'],{icon:1,time:100},function(){
                        window.location.reload();
                    })
                }else{
                    layer.alert(data['info'],{icon:2});
                }
            },'json').error(function(){
                layer.alert('请求失败',{icon:2});
            });
    });
    {{--重置密码--}}
    $('.row').on('click','.reset_btn',function(){
        var tourl = "{{to_route('admin.admin_list.password_reset')}}";
        var _this = $(this);
        var _id = _this.parents('tr').attr('data-id');
        var _index= layer.confirm("是否确认对该管理员重置密码?",{icon:3},function(){
            layer.close(_index);
            layer.prompt({title: '请输入重置新密码', formType: 1}, function(pass, index){
                layer.load(2);
                $.post(tourl,{id:_id,_token:"{!! csrf_token() !!}",password:pass},function(data){
                    layer.closeAll();
                    if(data['status'] > 0){
                        layer.msg(data['info'],{icon:1,time:100},function(){
                            window.location.reload();
                        })
                    }else{
                        layer.alert(data['info'],{icon:2});
                    }
                },'json').error(function(){
                    layer.closeAll();
                    layer.alert('请求失败',{icon:2});
                });
            });
        });
    });
</script>
@endsection

@section('content')
<!--body wrapper start-->
{{--内容页开始--}}
<div class="wrapper">

    <div class="row">
        <div class="col-sm-12">
            <section class="panel">
                <header class="panel-heading">
                    <div class="clearfix">
                        员工列表
                        <div class="btn-group pull-right">
                            <a role="button"  class="btn btn-info" href="{{to_route('admin.admin_list.add')}}">
                                添加 <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>
                </header>
                <div class="panel-body">
                    <div class="adv-table">

                        <table  class="display table table-bordered table-striped">
                            <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>登录名</th>
                                        <th>工号</th>
                                        <th>权限组</th>
                                        <th>职位</th>
                                        <th>姓名全拼</th>
                                        <th>电话号码</th>
                                        <th>排序值</th>
                                        <th>上次登录ip</th>
                                        <th>是否锁定</th>
                                        <th>操作</th>
                                    </tr>
                            </thead>
                            <tbody>
                                @foreach($adminList as $admin)
                                <tr class="gradeA" data-id="{{$admin['id']}}">
                                    <td>{{$admin['id']}}</td>
                                    <td>{{$admin['name']}}</td>
                                    <td>{{$admin['code']}}</td>
                                    <td>{{$admin['role_name']}}</td>
                                    <td>{{$admin['position_name']}}</td>
                                    <td>{{$admin['user_pin']}}</td>
                                    <td>{{$admin['phone']}}</td>
                                    <td>{{$admin['sort']}}</td>
                                    <td>{{$admin['login_ip']}}</td>
                                    <td>{{get_adminlist_status_name($admin['lock'])}}</td>
                                    <td style="width: 20%" class="center">
                                        <a href="{{to_route('admin.admin_list.edit',[$admin['id']])}}" class="btn btn-default btn-sm"><i class="fa fa-edit"></i> 修改</a>
                                        <button type="button" class="btn btn-default btn-sm del_btn"><i class="fa fa-trash-o"></i> 删除</button>
                                        @if($admin['lock'] == 1)
                                            <button type="button" class="btn btn-default btn-sm lock_btn"><i class="fa fa-unlock"></i> 锁定</button>
                                        @elseif($admin['lock'] == 2)
                                            <button type="button" class="btn btn-default btn-sm lock_btn"><i class="fa fa-lock"></i> 解锁</button>
                                        @endif
                                        <button type="button" class="btn btn-default btn-sm reset_btn"><i class="fa fa-undo"></i> 重置密码</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="row-fluid">

                            <div class="span6">
                                <div class="dataTables_paginate paging_bootstrap pagination">
                                    {{ $adminList->links('admin.layouts.page_html') }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
{{--内容页结束--}}
@endsection