@extends('admin.layouts.base')
@section('styles')
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-info fade in">
                <button type="button" class="close close-sm" data-dismiss="alert">
                    <i class="fa fa-times"></i>
                </button>

                <span>
                        数组格式的值:格式为 key:value <br>
                        配置名唯一:程序调用名 (new SysSetting)->getValue(配置名);
                </span>
            </div>

            <section class="panel">

                <header class="panel-heading">
                    配置编辑/添加
                </header>
                <div class="panel-body">
                    <div class=" form">
                        <form class="cmxform form-horizontal adminex-form" method="post" action="{{to_route('admin.setting.store')}}">

                            <div class="form-group ">
                                <label for="ccomment" class="control-label col-lg-2">配置名(唯一)</label>
                                <div class="col-lg-10">
                                    <input name="name" class="form-control" minlength="30" type="text" value="{{$setting['name'] ?? ''}}" required />
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="control-label col-lg-2">值类型</label>
                                <div class="col-lg-10">
                                    <select class="form-control" name="value_type">
                                        @foreach((new \App\Models\SysSetting())->getTypes() as $type => $title)
                                        <option value="{{$type}}" @if(!empty($setting['value_type']) && ($type == $setting['value_type'])) selected @endif >{{$title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group value-group value-type-1">
                                <label for="ccomment" class="control-label col-lg-2">值</label>
                                <div class="col-lg-10">
                                    <textarea class="form-control value-field">{!! $setting['value'] ?? '' !!}</textarea>
                                </div>
                            </div>

                            <div class="form-group value-group value-type-7">
                                <label for="ccomment" class="control-label col-lg-2">值</label>
                                <div class="col-lg-10">
                                    <textarea class="form-control value-field" rows="5">{!! json2row($setting['value'] ?? '') !!}</textarea>
                                </div>
                            </div>

                            <div class="form-group value-group value-type-2">
                                <label for="ccomment" class="control-label col-lg-2">值</label>
                                <div class="col-lg-10">
                                    <input class="form-control value-field" type="text" value="{{$setting['value'] ?? ''}}">
                                </div>
                            </div>

                            <div class="form-group value-group value-type-3">
                                <label for="ccomment" class="control-label col-lg-2">值</label>
                                <div class="col-sm-10">
                                    <div class="input-group m-bot15">
                                        <input type="text" class="form-control value-field" value="{{$setting['value'] ?? ''}}">
                                        <div class="input-group-btn">
                                            <button tabindex="-1" class="btn btn-default" type="button" id="pickfiles">上传</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="ccomment" class="control-label col-lg-2">标题</label>
                                <div class="col-lg-10">
                                    <input name="label" class=" form-control" minlength="30" type="text" value="{{$setting['label'] ?? ''}}" required />
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    {{csrf_field()}}
                                    <button class="btn btn-primary ajax-post-setting" type="submit">保存</button>
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
    <script type="text/javascript" src="/assets/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="/qiniu-sdk/plupload/js/moxie.js"></script>

    <!--这个文件也不不用-->
    <script type="text/javascript" src="/qiniu-sdk/qiniu/demo/scripts/ui.js"></script>


    <script type="text/javascript" src="/qiniu-sdk/plupload/js/plupload.dev.js"></script>
    <script type="text/javascript" src="/qiniu-sdk/qiniu/src/qiniu.js"></script>

    <script>
        $(function(){
            var valueType = $("select[name='value_type']").val();
            $(".value-group").hide();
            $(".value-type-"+valueType).show();
            $(".value-type-" + valueType).find(".value-field").attr("name","value");


            $("select[name='value_type']").change(function(){
                var valueType = $(this).val();
                $(".value-group").hide();
                $(".value-type-"+valueType).show();
                $(".value-type-" + valueType).find(".value-field").attr("name","value");
            });

        });

        $(".ajax-post-setting").click(function(){
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
                        location.href = "{{to_route('admin.setting')}}";
                    }
                },
                error : function(){
                    layer.closeAll('loading');
                    layer.alert('请求超时',{icon:2});
                }
            });
            return false;
        });


        $(function() {
            var uploader = Qiniu.uploader({
                runtimes: 'html5,flash,html4',
                browse_button: 'pickfiles',
                max_file_size: '1000mb',
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
                        $.post("{!! to_route('uploadfile') !!}",{info:info,size:file.size,_token:"{!! csrf_token() !!}"},function (result) {
                            if (result.status == -1) {
                                layer.msg(result.msg,{icon:2});
                                return false;
                            }

                            $(".value-type-3 .value-field").val(result.data.url);
                            var progress = new FileProgress(file, 'fsUploadProgress');
                            progress.setComplete(up, info, result.data);
                        },'json');
                    }
                }
            });
        });

    </script>
@endsection