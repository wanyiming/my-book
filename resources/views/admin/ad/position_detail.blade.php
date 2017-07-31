@extends('admin.layouts.base')
@section('styles')
    <link rel="stylesheet" href="/assets/viewerjs/dist/viewer.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <section class="panel">
                <header class="panel-heading">
                    <div class="clearfix">
                        广告列表
                        <a class="ad-edit btn btn-success" >添加广告</a> <a href="javascript:history.go(-1);" class="btn btn-primary">返回</a>
                    </div>
                </header>
                <div class="panel-body">
                    <table class="table table-bordered" id="imageView">
                        @if(!$lists->isEmpty())
                        @foreach($lists as $item)
                        <tr>
                            <td class="col-md-1 text-right">
                                <img src="{{$item['picture_url']}}" alt="" width="200px" height="200px">
                            </td>
                            <td>
                                <p>{{$item['ad_name']}}</p>
                                <p>{{$item['ad_link']}}</p>
                                <p>开始时间:{{$item['begin_time']}}</p>
                                <p>结束时间:{{$item['end_time']}}</p>
                                <p>当前状态: <label for="" class="{{$status[$item['status']]['class'] ?? ''}}">{{$status[$item['status']]['name'] ?? ''}}</label></p>
                                <p><a class="ad-edit btn btn-warning"
                                      data-id="{{$item['id']}}"
                                      data-picture_url="{{$item['picture_url']}}"
                                      data-ad_name="{{$item['ad_name']}}"
                                      data-ad_link="{{$item['ad_link']}}"
                                      data-begin_time="{{$item['begin_time']}}"
                                      data-end_time="{{$item['end_time']}}"
                                      data-weight="{{$item['weight']}}"
                                      data-status="{{$item['status']}}"
                                    >修改</a></p>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </table>

                </div>
            </section>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">编辑广告</h4>
                    </div>
                    <div class="modal-body row">
                        <form action="{{to_route('admin.ad.ad_save')}}" method="post">
                            {{csrf_field()}}
                            <input type="hidden" name="position_id" value="{{$positionId ?? ''}}">
                            <input type="hidden" name="id" value="">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 text-right"> 图片</label>
                                    <div class="col-md-9">
                                        <div class=" input-group" id="imagesContainer">
                                            <input name="picture_url" value="" class="form-control ">
                                            <div class="input-group-addon">
                                                <div style="width: 75px;height: 22px;margin-top:-2px;overflow:hidden;">
                                                    <input type="file" id="file" class="file">
                                                </div>
                                            </div>
                                        </div>
                                        <span class="help-block alert-danger"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 text-right"> 广告名称</label>
                                    <div class="col-md-9">
                                        <input name="ad_name" value="" class="form-control ">
                                        <span class="help-block alert-danger"></span>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 text-right"> 广告链接</label>
                                    <div class="col-md-9">
                                        <input name="ad_link" value="" class="form-control ">
                                        <span class="help-block alert-danger"></span>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 text-right"> 开始时间</label>
                                    <div class="col-md-9">
                                        <input name="begin_time" id="begin_time" value="" class="form-control ">
                                        <span class="help-block alert-danger"></span>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 text-right"> 结束时间</label>
                                    <div class="col-md-9">
                                        <input name="end_time" id="end_time" value="" class="form-control ">
                                        <span class="help-block alert-danger"></span>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 text-right"> 排序</label>
                                    <div class="col-md-9">
                                        <input name="weight" value="10" class="form-control ">
                                        <span class="help-block alert-danger"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 text-right"> 状态</label>
                                    <div class="col-md-9">
                                        @foreach($status as $key=>$value)
                                            <input type="radio" checked name="status" value="{{$key}}"> {{$value['name']}}
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="pull-right">
                                    <button id="ad-save" class="btn btn-success btn-sm" type="button"> 保存</button>
                                </div>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
        <!-- modal -->

    </div>
@endsection

@section('scripts')
    <script src="/assets/laydate/laydate.js"></script>
    <script src="/assets/viewerjs/dist/viewer.js"></script>
    <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
    <script src="/assets/jquery_file_upload/js/vendor/jquery.ui.widget.js"></script>
    <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
    <script src="/assets/jquery_file_upload/js/jquery.iframe-transport.js"></script>
    <!-- The basic File Upload plugin -->
    {{--<script src="/assets/jquery_file_upload/js/jquery.fileupload.js"></script>--}}


    <script type="text/javascript" src="/qiniu-sdk/plupload/js/moxie.js"></script>
    <script type="text/javascript" src="/qiniu-sdk/plupload/js/plupload.dev.js"></script>
    <script type="text/javascript" src="/qiniu-sdk/qiniu/src/qiniu.js"></script>

    <script>
        new Viewer(document.getElementById('imageView'));
        $(function () {
            laydate({
                elem: '#begin_time',
                event: 'focus', //响应事件。如果没有传入event，则按照默认的click
                format: 'YYYY-MM-DD hh:mm:ss',
                istime: true,
                choose: function (dates) { //选择好日期的回调
                }
            });
            laydate({
                elem: '#end_time',
                event: 'focus', //响应事件。如果没有传入event，则按照默认的click
                format: 'YYYY-MM-DD hh:mm:ss',
                istime: true,
                choose: function (dates) { //选择好日期的回调
                }
            });

            $(".ad-edit").on('click', function () {
                var id = $(this).attr('data-id');
                var picture_url = $(this).attr('data-picture_url');
                var ad_name = $(this).attr('data-ad_name');
                var ad_link = $(this).attr('data-ad_link');
                var begin_time = $(this).attr('data-begin_time');
                var end_time = $(this).attr('data-end_time');
                var weight = $(this).attr('data-weight');
                var status = $(this).attr('data-status');
                $("input[name=id]").val(id);
                $("input[name=picture_url]").val(picture_url);
                $("input[name=ad_name]").val(ad_name);
                $("input[name=ad_link]").val(ad_link);
                $("input[name=begin_time]").val(begin_time);
                $("input[name=end_time]").val(end_time);
                $("input[name=weight]").val(weight ? weight : 0);
                $("input[name=status]").each(function(){
                    if ($(this).val() == status) {
                        $(this).attr('checked',true);
                    } else {
                        $(this).removeAttr('checked');
                    }
                });
                $('#myModal').modal();
            });

            $("#ad-save").on('click',function(){
                $.ajax({
                    type:"post",
                    url:$(this).closest('form').attr('action'),
                    data:$(this).closest('form').serialize(),
                    success:function(response){
                        layer.msg('操作成功',{icon:1});
                        $('#myModal').modal('hide');
                        window.location.reload();
                    },
                    error:function(errorBag){
                        var responseJson = errorBag.responseJSON;
                        for(var field in responseJson){
                            $("input[name='"+field+"']").closest(".form-group").find('.help-block').text(responseJson[field].pop());
                        }
                    },
                    dataType:'json'
                });
            });
        });

        //       封面图上传
        $(function () {
            var uploader = Qiniu.uploader({
                runtimes: 'html5,flash,html4',
                browse_button: 'file',
                container: 'imagesContainer',
                drop_element: 'imagesContainer',
                max_file_size: '20mb',
                flash_swf_url: 'bower_components/plupload/js/Moxie.swf',
                dragdrop: true,
                chunk_size: '4mb',
                multi_selection: !(mOxie.Env.OS.toLowerCase()==="ios"),
                uptoken_url: "{!! to_route('uptoken') !!}",
                domain: '{!! \App\Libraries\Qiniu\FileManagement::DOMAIN !!}',
                get_new_uptoken: false,
                auto_start: true,
                log_level: 5,
                init: {
                    'FileUploaded': function(up, file, info) {
                        var fileInfp = eval("("+info+")");
                        var imagePaht ='{!! \App\Libraries\Qiniu\FileManagement::DOMAIN !!}' + fileInfp.key;
                        $("input[name='picture_url']").val(imagePaht);
                    },
                    'Error': function(up, err, errTip) {
                        //上传出错时,处理相关的事情
                        layer.msg(err.file,{icon:2}); return false;
                    },'Key':function() {
						
					}
                }
            });
        });

    </script>
@endsection