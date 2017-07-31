@extends('admin.layouts.base')
@section('scripts')
    <script src="/assets/js/a_public.js"></script>
@endsection
@section('content')
    <div class="wrapper">
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        友情链接列表
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                         </span>
                    </header>
                    <div class="panel-body">
                        <div class="adv-table ">
                            <div class="clearfix">
                                <div class="btn-group">
                                    <a href="{{to_route('admin.friendly_link.add')}}">
                                        <button id="editable-sample_new" class="btn btn-primary">
                                            添加类型 <i class="fa fa-plus"></i>
                                        </button>
                                    </a>
                                </div>
                                <div class="btn-group pull-right">
                                    <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">工具 <i class="fa fa-angle-down"></i>
                                    </button>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="{{url($_SERVER['REQUEST_URI'])}}">刷新</a></li>
                                        <li><a href="javascript:;">导出PDF</a></li>
                                        <li><a href="javascript:;">导出Excel</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="space15"></div>
                            <div id="editable-sample_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                <form action="" method="get">
                                    <div class="row">
                                        <div class="dataTables_length" id="editable-sample_length" style="min-height: 70px;">
                                            <div class="col-lg-2">
                                                <label for="" style="line-height: 30px"> <strong>信息总量：{{$lists->total()}}</strong></label>
                                            </div>
                                            <div style="float: right; padding-right: 15px;">
                                                <div class="col-lg-2" style="width:auto;">
                                                    <label>类型名称: <input class="form-control" maxlength="20" placeholder="网站名称/id" aria-controls="editable-sample" name="website" value="{{request('website')}}" type="text"></label>
                                                </div>
                                                <div class="col-lg-2" style="width:auto;">
                                                    <label>内容搜索: <input class="form-control" maxlength="20" placeholder="根据介绍搜索" aria-controls="editable-sample" name="content" value="{{request('content')}}" type="text"></label>
                                                </div>
                                                <button class="btn btn-search" type="submit"><i class="fa fa-search-minus"></i>搜索</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <table aria-describedby="editable-sample_info" class="table table-striped table-hover table-bordered dataTable" id="editable-sample">
                                    <thead>
                                    <tr role="row">
                                        <th><input type="checkbox" value="parents" name=""></th>
                                        <th>id</th>
                                        <th>网站名称</th>
                                        <th>网站地址</th>
                                        <th>是否推荐</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody aria-relevant="all" aria-live="polite" role="alert">
                                    <?php $listsWhere = array_pluck($lists,NULL,'id');?>
                                    @foreach($listsWhere as $k=>$v)
                                        <tr class="odd">
                                            <td class=""><input type="checkbox" name="child[]" value="{{$v['id']}}"></td>
                                            <td class="  sorting_1">{{$v['id']}}</td>
                                            <td class=" "> {{$v['website']}}</td>
                                            <td class=" "> {{$v['weburl']}}</td>
                                            <td class=" "> {!! recommend_info(\App\Models\Recommend::OBJECT_TYPE_FRIENDLY, $v['id']) !!}</td>
                                            <td class=" ">
                                                <a href="{{to_route('admin.friendly_link.edit',['id'=>$v['id']])}}"><button class="btn btn-default btn-xs" type="button"><i class="fa fa-pencil"></i> 修改</button></a>
                                                @if($v['status'] == 1)
                                                    <button class="btn btn-demo btn-success btn-xs"><i class="fa fa-warning"></i> 已通过</button>
                                                @elseif($v['status'] == 2)
                                                    <button class="btn btn-demo btn-xs"><i class="fa fa-warning"></i> 未通过</button>
                                                @else
                                                <a href="{{to_route('admin.friendly_link.examine',['id'=>$v['id']])}}"><button class="btn btn-info btn-xs" type="button"><i class="fa fa-pencil"></i>审核</button></a>
                                                @endif
                                                @if($v['status'] !== 99)
                                                    <button class="btn btn-danger btn-xs" url="{!! to_route('admin.friendly_link.del',['id' => $v->id,'_token' => csrf_token()]) !!}" onclick="return ajax_post($(this),'确定删除该信息？')"><i class="fa fa-trash-o"></i> 删除</button>
                                                @else
                                                    <button class="btn btn-warning btn-xs" ><i class="fa fa-warning"></i> 已删除</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="dataTables_info" id="editable-sample_info"></div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="dataTables_paginate paging_bootstrap pagination">
                                            {!! $lists->appends($where)->links('admin.layouts.page_html') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection