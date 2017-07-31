@extends('admin.layouts.base')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <section class="panel">
                <div class="panel-body">
                    <a class="btn btn-primary" href="{{to_route('admin.setting.create')}}">添加配置</a>
                    <div class="adv-table ">
                        <div id="editable-sample_wrapper" class="dataTables_wrapper form-inline" role="grid">
                            <table aria-describedby="editable-sample_info" class="table table-striped table-hover table-bordered dataTable" id="editable-sample">
                                <thead>
                                <tr role="row">
                                    <th>标题</th>
                                    <th>name</th>
                                    <th>值</th>
                                    <th>类型</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody aria-relevant="all" aria-live="polite" role="alert">
                                @foreach($lists as $setting)
                                <tr class="odd">
                                    <td class="col-sm-1">{{$setting['label'] ?? ''}}</td>
                                    <td class="col-sm-2">{{$setting['name'] ?? ''}}</td>
                                    <td class="col-sm-7">
                                        <pre>{{var_export($setting->getValue(),true)}}</pre>

                                    </td>
                                    <td>{{$setting->type2cn()}}</td>
                                    <td>
                                        <a href="{{to_route('admin.setting.edit',['name'=>$setting['name']])}}">编辑</a>
                                        <a class="ajax-delete" href="{{to_route('admin.setting.delete',['name'=>$setting['name']])}}">删除</a>
                                    </td>
                                </tr>
                                </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    <div class="dataTables_paginate paging_bootstrap pagination">
                        {!! $lists->links('admin.layouts.page_html') !!}
                    </div>

                </div>
            </section>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(function(){
            $(".ajax-delete").click(function(){
                var url = $(this).attr("href");
                if(window.confirm("删除会无法恢复,确定删除吗?")){
                    $.post(url,{"_token":"{{csrf_token()}}"},function(res){
                        window.location.href = res.data.jump_url;
                    },"json");

                }
                return false;
            });
        });
    </script>
@endsection