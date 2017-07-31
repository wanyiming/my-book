@extends('admin.layouts.base')
@section('styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-fileupload.min.css" />
@endsection
@section('content')
<div class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    添加/编辑书本信息
                </header>
                <div class="panel-body">
                    <form role="form" class="form-horizontal adminex-form" method="post" onsubmit=" return saveBook();" action="{{route('admin.books.store')}}">
                        {!! csrf_field() !!}
                        <input type="hidden" name="id" value="{{$info['id'] ?? 0}}" />
                        <div class="form-group">
                            <label class="col-sm-2 control-label">*书名</label>
                            <div class="col-sm-2">
                                <input maxlength="30" type="text" class="form-control" name="title" value="{{$info['title'] ?? ''}}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">作者</label>
                            <div class="col-sm-2">
                                <input maxlength="30" type="text" class="form-control" name="author" value="{{$info['author'] ?? ''}}" />
                            </div>
                            <label class="col-sm-1 control-label">来源链接</label>
                            <div class="col-sm-3">
                                <input maxlength="30" type="text" class="form-control" name="url" value="{{$info['url'] ?? ''}}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label col-lg-2">*类目</label>
                            <div class="col-lg-2">
                                <select class="form-control" name="book_type">
                                    @foreach($bookType as $typeKey=>$valueType)
                                        <option value="{{$typeKey}}" @if(!empty($info->book_type)))@if(!$info->book_type == $typeKey) selected @endif @endif>{{$valueType}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">*字数</label>
                            <div class="col-sm-2">
                                <input maxlength="30" type="text" class="form-control" name="font_size" value="{{$info['font_size'] ?? ''}}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">封面</label>
                            <div class="col-md-9">
                                <div class="fileupload" id="imagesContainer">
                                    <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                        <img src="{{empty($info->book_cover) ? asset('assets/images/-text.png') : $info->book_cover}}" alt="" />
                                        <input type="hidden" name="book_cover" value="{{$info->book_cover ?? ''}}">
                                    </div>
                                    <div>
                                        <span class="btn btn-default btn-file" id="btn-file">
                                            <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 上传封面</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">书本状态</label>
                            <div class="col-sm-2">
                                <label><input type="radio" name="type_id" value="1" @if(empty($info['type_id']) || $info['type_id'] == 1) checked @endif> 全本</label>
                                <label><input type="radio" name="type_id" value="2" @if(!empty($info['type_id']) && $info['type_id'] == 2) checked @endif> 连载</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">状态</label>
                            <div class="col-sm-2">
                                <label><input type="radio" name="status" value="1" @if(empty($info['status']) || $info['status'] == 1) checked @endif> 启用</label>
                                <label><input type="radio" name="status" value="2" @if(!empty($info['status']) && $info['status'] == 2) checked @endif> 暂停</label>
                                <label><input type="radio" name="status" value="2" @if(!empty($info['status']) && $info['status'] == 99) checked @endif> 已删除</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">最新章节</label>
                            <div class="col-sm-3">
                                <input maxlength="30" type="text" class="form-control" name="update_fild" value="{{$info['update_fild'] ?? ''}}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">最新更新时间</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="update_time" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:00'})" value="{{$info['update_time'] ?? ''}}" />
                            </div>
                            <div class="help-block">配合草稿用才生效并会自动到点发布,精确到分钟</div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">推荐的票</label>
                            <div class="col-sm-2">
                                <input maxlength="30" type="text" class="form-control" name="recom_num" value="{{$info['recom_num'] ?? ''}}" />
                            </div>
                            <label class="col-sm-1 control-label">阅读量</label>
                            <div class="col-sm-2">
                                <input maxlength="30" type="text" class="form-control" name="read_num" value="{{$info['read_num'] ?? ''}}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">书名简介</label>
                            <div class="col-sm-10">
                                <textarea maxlength="300" rows="10"  class="form-control" name="profiles">{{$info['profiles'] ?? ''}}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button class="btn btn-info submit_btn">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    提交
                                </button>
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
@endsection
@section('scripts')
    <script type="text/javascript" src="/qiniu-sdk/plupload/js/moxie.js"></script>
    <script type="text/javascript" src="/qiniu-sdk/plupload/js/plupload.dev.js"></script>
    <script type="text/javascript" src="/qiniu-sdk/qiniu/src/qiniu.js"></script>
    <script>
        //封面图上传
        $(function () {
            var uploader = Qiniu.uploader({
                runtimes: 'html5,flash,html4',
                browse_button: 'btn-file',
                container: 'imagesContainer',
                drop_element: 'imagesContainer',
                max_file_size: '1000mb',
                flash_swf_url: 'bower_components/plupload/js/Moxie.swf',
                dragdrop: true,
                chunk_size: '4mb',
                multi_selection: !(mOxie.Env.OS.toLowerCase()==="ios"),
                uptoken_url: "{!! to_route('uptoken') !!}",
                domain: '{!! \App\Libraries\Qiniu\FileManagement::DOMAIN !!}',
                get_new_uptoken: false,
                auto_start: true,
                filters : {
                    max_file_size : '100mb',
                    prevent_duplicates: false,
                    mime_types: [
                        {title : "Image files", extensions : "jpg,gif,png"}, // 限定jpg,gif,png后缀上传
                    ]
                },
                log_level: 5,
                init: {
                    'FilesAdded': function(up, files) {
                        plupload.each(files, function(file) {
                            // 文件添加进队列后,处理相关的事情
                        });
                    },
                    'BeforeUpload': function(up, file) {
                        // 每个文件上传前,处理相关的事情

                    },
                    'UploadProgress': function(up, file) {
                        // 每个文件上传时,处理相关的事情
                    },
                    'FileUploaded': function(up, file, info) {
                        var fileInfp = eval("("+info+")");
                        var imagePaht ='{!! \App\Libraries\Qiniu\FileManagement::DOMAIN !!}' + fileInfp.key;
                        $("input[name='book_cover']").val(imagePaht).prev().attr('src', imagePaht);
                    },
                    'Error': function(up, err, errTip) {
                        //上传出错时,处理相关的事情
                        layer.msg(errTip,{icon:2}); return false;
                    },
                    'Key': function(up, file) {
                    }
                }
            });
        });
    </script>
    <script>
        function  saveBook() {
            _fromSubmit($(".adminex-form"), 'post');
            return false;
        }
    </script>
@endsection