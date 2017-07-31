@extends('admin.layouts.base')
@section('styles')
    <link rel="stylesheet" href="/assets/viewerjs/dist/viewer.css">
@endsection
@section('content')
    <div class="row">
        <header class="panel-heading">
            <div class="clearfix">
                {{--@if($goods->status == \App\Models\Goods::GOODS_STATUS_AUDIT)--}}
                    {{--审核管理 / <a href="{{to_route('admin.goods.pending')}}">商品信息-待审核</a> / 商品详情--}}
                    {{--@else--}}
                    {{--客户管理 / <a href="{{to_route('admin.goods.lists')}}">商品管理</a> / 商品详情--}}
                {{--@endif--}}
            </div>
        </header>
    </div>
    <div id="imageView2" class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        店铺资料
                    </div>
                    <div class="panel-body" id="imageView">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="col-md-2 text-right">网站名称：</td>
                                    <td>{{$info->website}}</td>
                                </tr>
                                <tr>
                                    <td class="col-md-2 text-right">网站连接：</td>
                                    <td>{{$info->weburl}}</td>
                                </tr>
                                <tr>
                                    <td class="col-md-2 text-right">邮箱：</td>
                                    <td>{{$info->email}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        网站介绍
                    </div>
                    <div class="panel-body" id="imageView">
                        {{$info->content}}
                    </div>
                </div>
            </div>
        </div>
        @if($info->status == \App\models\FriendlyLink::LINK_STATUS_PENDING)
            <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading">
                            操作
                        </div>
                        <div class="panel-body" id="imageView">
                            <div class="btn-group">
                                <a href="javascript:history.back(-1)">
                                    <button id="editable-sample_new" class="btn btn-default">
                                        返回
                                    </button>
                                </a>
                            </div>
                            <div  class="btn-group" style="float: right">
                                <a href="javascript:;" style="float: right">
                                    <form action="" method="post">
                                        <button id="editable-sample_new" data-id="{{\App\models\FriendlyLink::LINK_STATUS_FAIL}}"  class="btn btn-warning">
                                            拒绝审核
                                        </button>
                                    </form>
                                </a>
                                <a href="javascript:;" style="float: right">
                                    <form action="{{to_route('admin.friendly_link.change_status')}}" method="post">
                                        {{csrf_field()}}
                                        <input type="hidden" name="status" value="{{\App\models\FriendlyLink::LINK_STATUS_SUCCESS}}">
                                        <input type="hidden" name="id" value="{{$info->id}}">
                                        <button id="" data-id="" type="button" class="btn btn-success submit_btn">审核通过</button>
                                    </form>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="dialog-reason hidden">
        <form action="{{to_route('admin.friendly_link.change_status')}}" method="post">
            {{csrf_field()}}
            <input type="hidden" name="id" value="{{$info->id}}">
            <input type="hidden" name="status" value="">
            <div class="form-group {{ $errors->has('attr_title') ? ' has-error' : '' }}">
                <div class="col-lg-12">
                    <textarea class="form-control" rows="3" id="content" name="content"></textarea>
                    <span class="help-block">拒绝原因</span>
                </div>
            </div>
            <div class="form-group" style="float: right">
                <div class="col-lg-4"><button type="button" class="btn btn-primary submit_btn">保存</button></div>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script src="/assets/viewerjs/dist/viewer.js"></script>
    <script src="/static/ueditor/lyr.config.js"></script>
    <script>
        $(function () {
            $(".btn-warning").click(function () {
                $("input[name='status']").val($(this).attr('data-id'));
                layer.open({
                    type: 1,
                    title: '拒绝',
                    shadeClose: false,
                    shade: 0.8,
                    area: ['600px', '250px'],
                    content: $(".dialog-reason").html()
                });
                return false;
            });

        });
    </script>
    <script>
        $('body').on('click','.submit_btn',function () {
            var _this = $(this);
            window.layer.closeAll();
            var _url = _this.parents('form').attr('action');
            var _forminfo = _this.parents('form').serialize();
            $.post(_url,_forminfo,function (data) {
                if(data['status'] > 0){
                    layer.alert(data['msg'],function () {
                        //window.location.reload();
						location.href = "{!! to_route('admin.friendly_link.lists') !!}";
                    });
                }else{
                    layer.msg(data['msg'],{icon:2});
                }
            })
        })
    </script>
@endsection
