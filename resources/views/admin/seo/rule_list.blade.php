@extends('admin.layouts.base')
@section('content')
    <div class="wrapper">
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        SEO列表
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                         </span>
                    </header>
                    <div class="panel-body">
                        <div class="adv-table ">
                            <div class="clearfix">
                                <div class="btn-group">
                                    <a href="{{to_route('admin.seo.add')}}">
                                        <button id="editable-sample_new" class="btn btn-primary">
                                            添加新SEO <i class="fa fa-plus"></i>
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
                                                <label for="" style="line-height: 30px"> <strong>信息总量：{{count($lists)}}</strong></label>
                                            </div>
                                            <div style="float: right; padding-right: 15px;">
                                                <div class="col-lg-2" style="width:auto;">
                                                    <label>名称: <input class="form-control" maxlength="20" aria-controls="editable-sample" placeholder="" name="title" value="{{$where['title'] ?? ''}}" type="text"></label>
                                                </div>
                                                <button class="btn btn-search" type="submit"><i class="fa fa-search-minus"></i>搜索</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <table aria-describedby="editable-sample_info" class="table table-striped table-hover table-bordered dataTable" id="editable-sample">
                                    <thead>
                                    <tr role="row">
                                        <th>ID</th>
                                        <th>名称</th>
                                        <th>title</th>
                                        <th>keywords</th>
                                        <th>desctipsion</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody aria-relevant="all" aria-live="polite" role="alert">
                                    @foreach($lists as $key=>$value)
                                        <tr class="odd">
                                            <td class="  sorting_1">{{ $value->id }}</td>
                                            <td class=" "> {{ $value->page_name }}</td>
                                            <td class=" "> {{ $value->title }}</td>
                                            <td class=" "> {{ $value->keywords }}</td>
                                            <td class=" "> {{ $value->description }}</td>
                                            <td>
                                                <a href="{!! to_route('admin.seo.edit',['id'=>$value->id]) !!}">
                                                    <button class="btn btn-warning btn-xs" ><i class="fa fa-pencil"></i> 修改</button>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection