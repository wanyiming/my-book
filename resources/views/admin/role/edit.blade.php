@extends('admin.layouts.base')
@section('styles')
    <style>
        .panel{
            border: #ddd 1px dashed;
        }
        .panel-heading,.panel-body{
            padding:0;
        }
        .panel-heading,.panel-body{
            padding-left:25px;
            padding-top:5px;
        }
        .panel-body{
            padding-bottom: 5px;
        }
        .panel-item{
            display: inline-block;
        }
    </style>
@endsection

@section('content')
<div class="wrapper">

    <div class="panel panel-default">
        <div class="panel-body">

            <div class="form-group">
                <label class="col-sm-1 control-label">角色名</label>
                <div class="col-sm-11">
                    <input type="text" class="form-control" name="name" value="{{$role['name'] ?? ''}}">
                </div>
            </div>
        </div>

        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-1 control-label">备注</label>
                <div class="col-sm-11">
                    <textarea class="form-control" name="remark">{{$role['remark'] ?? ''}}</textarea>
                </div>
            </div>
        </div>

        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-1 control-label">启/停用</label>
                <div class="col-sm-11">
                    <label for="status radio"><input type="radio" name="status" value="1" @if(empty($role['status']) || $role['status'] == 1) checked @endif>启用</label>
                    <label for="status radio"><input type="radio" name="status" value="2" @if(!empty($role['status']) && $role['status'] == 2) checked @endif>停用</label>
                </div>
            </div>
        </div>

    </div>
    <div class="row form-inline panel-body">
        <div class="checkbox">
            <label>
                <input type="checkbox" value=""> 全选
            </label>
        </div>
        @include('admin.role.role_node')
    </div>

    <div class="panel-body">
        {{csrf_field()}}
        <input type="hidden" name="id" value="{{$role['id'] ?? 0}}">
        <button class="btn btn-primary ajax-save-btn" type="button"> 保 存 </button>
    </div>

</div>
@endsection

@section('scripts')
<script>
    $(function(){
        $("input[type='checkbox']").change(function(){
            if($(this).is(":checked")){
                $(this).parents(".panel").prev(".checkbox").find("input[type='checkbox']").prop("checked",true);
                $(this).closest(".panel-body").find("input[type='checkbox']").prop("checked",true);
            }else{
                $(this).closest(".panel-body").find("input[type='checkbox']").prop("checked",false);
            }
        });

        $(".ajax-save-btn").click(function () {
            $.post("{{to_route('admin.role.save')}}", $("input,textarea").serialize(), function (res) {
                if(1 == res.status){
                    layer.alert("保存成功");
                    window.location.href = "{{to_route('admin.role.lists')}}";
                }else{
                    layer.alert(res.msg);
                }
            }, "json");
        });

    });
</script>
@endsection