@extends('admin.layouts.base')
@section('styles')
<!--dynamic table-->
<link href="/assets/js/advanced-datatable/css/demo_page.css" rel="stylesheet" />
<link href="/assets/js/advanced-datatable/css/demo_table.css" rel="stylesheet" />
<link rel="stylesheet" href="/assets/js/data-tables/DT_bootstrap.css" />
@endsection
@section('scripts')

@endsection

@section('content')
<!--body wrapper start-->
{{--内容页开始--}}
<div class="wrapper">

    <div class="row">
        <div class="col-sm-12">
            <section class="panel">
                <header class="panel-heading">
                    <div class="clearfix">
                        角色列表
                        <div class="btn-group pull-right">
                            <a role="button"  class="btn btn-info" href="{{to_route('admin.role.add')}}">
                                添加 <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>
                </header>
                <div class="panel-body">
                    <div class="adv-table">
                        {{--信息列表开始--}}
                        <table class="display table table-bordered table-striped">
                            <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>名称</th>
                                        <th>备注</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                            </thead>
                            <tbody>
                                @foreach($roleList as $role)
                                <tr class="gradeA">
                                    <td>{{$role['id']}}</td>
                                    <td>{{$role['name']}}</td>
                                    <td>{{$role['remark']}}</td>
                                    <td>{{['1'=>'使用中','2'=>'已停用'][$role['status']] ?? ''}}</td>
                                    <td style="width: 20%" class="center">
                                        <a role="button" href="{{to_route('admin.role.edit',['id' => $role['id']])}}" class="btn btn-info">编辑</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{--信息列表结束--}}
                        {{--分页html开始--}}
                        {{--{{ $roleList->links() }}--}}
                        <div class="row-fluid">

                            <div class="span6">
                                <div class="dataTables_paginate paging_bootstrap pagination">
                                    {{ $roleList->links('admin.layouts.page_html') }}
                                </div>
                            </div>
                        </div>
                        {{--分页html结束--}}
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
{{--内容页结束--}}
@endsection