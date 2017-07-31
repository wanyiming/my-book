@extends('admin.layouts.base')
@section('styles')
    <link rel="stylesheet" type="text/css" href="/assets/js/nestable/jquery.nestable.css" />
@endsection
@section('content')
    <div class="panel-body">
        <div class="alert alert-info fade in">
            <button type="button" class="close close-sm" data-dismiss="alert">
                <i class="fa fa-times"></i>
            </button>
            <span>
                文章类目用于文章分类.<br>
                最多允许三级节点 <br>
            </span>
        </div>
        <a class="btn btn-primary" href="{{to_route('admin.book_type.add')}}">添加文章分类</a>
        <div class="dd" id="node">
            @include('admin.book_type.node')
        </div>
    </div>
@endsection
@section('scripts')
    <script src="/assets/js/nestable/jquery.nestable.js"></script>
    <script>
        $('#node').nestable();
        $('#node').nestable('collapseAll');
        $(".ajax-post-node-delete").click(function(){
            layer.load(2);
            $.ajax({
                type : 'post',
                url : $(this).attr("href"),
                data : {"_token":"{{csrf_token()}}"},
                dataType : 'json',
                success : function(data){
                    layer.closeAll('loading');
                    if(data.status != 1){
                        layer.alert(data.msg,{icon: 2});
                        return false;
                    }else{
                        location.href = "{{to_route('admin.article.category.lists')}}";
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
