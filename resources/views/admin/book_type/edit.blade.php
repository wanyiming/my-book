@extends('admin.layouts.base')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    后台节点编辑/添加
                </header>
                <div class="panel-body">
                    <div class=" form">
                        <form class="form form-horizontal adminex-form" method="post" action="{{to_route('admin.book_type.save')}}">
                            <div class="form-group ">
                                <label for="cname" class="control-label col-lg-2">上级节点</label>
                                <div class="col-lg-10">
                                    <input class=" form-control" minlength="20" type="text" required value="{{$parentName}}" readonly/>
                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="ccomment" class="control-label col-lg-2">节点名</label>
                                <div class="col-lg-10">
                                    <input name="name" class="form-control" minlength="30" type="text" value="{{$typeInfo['name'] ?? ''}}" required />
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="agree" class="control-label col-lg-2 col-sm-3">是否启用</label>
                                <div class="col-lg-10 col-sm-9">
                                    <input type="checkbox" style="width: 20px" class="checkbox form-control" name="status" value="1" @if($typeInfo['status'] ?? 1)) checked @endif/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    {{csrf_field()}}
                                    <input type="hidden" name="parent_id" value="{{$parentId ?? ''}}">
                                    <input type="hidden" name="id" value="{{$typeInfo['id'] ?? ''}}">
                                    <button class="btn btn-primary ajax-post-node" type="submit">保存</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </section>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $(".ajax-post-node").click(function(){
            layer.load(2);
            $.ajax({
                type : 'post',
                url : $(this).closest("form").attr("action"),
                data : $(this).closest("form").serialize(),
                dataType : 'json',
                success : function(data){
                    layer.closeAll('loading');
                    if(data.status != 1){
                        layer.alert(data.msg,{icon: 2});
                        return false;
                    }else{
                        location.href = "{{to_route('admin.book_type.lists')}}";
                    }
                },
                error : function(){
                    layer.closeAll('loading');
                    layer.alert('请求超时',{icon:2});
                }
            });
            return false;
        });
    </script>
@endsection