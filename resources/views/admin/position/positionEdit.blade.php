@extends('admin.layouts.base')
@section('styles')
@endsection
@section('scripts')
    <script src="/static/new_layer/layer.js"></script>
    <script>
        {{--节点添加修改--}}
        $('.row').on('click','.submit_btn',function(){
            var tourl = $(this).parents('form').attr('action');
            $.post(tourl,$(this).parents('form').serialize(),function(data){
                if(data['status'] > 0){
                    layer.msg(data['info'],{icon:1,time:100},function(){
                        window.location.reload();
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
                        <form role="form" class="form-horizontal adminex-form" method="post" action="{{to_route('admin.position.save_position')}}">
                            {!! csrf_field() !!}
                            <input type="hidden" name="id" value="{{$info['id']}}" />
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">职位名称</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="name" value="{{$info['name']}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">所属部门</label>
                                <div class="col-lg-2">
                                    <select class="form-control m-bot15" name="role_id">
                                        @foreach($role_list as $kr=>$vr)
                                            <option value="{{$kr}}" @if($info['role_id'] == $kr) selected @endif>{{$vr}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">备注</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="remarks" value="{{$info['remarks']}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">排序</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="sort" value="{{$info['sort']}}">
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