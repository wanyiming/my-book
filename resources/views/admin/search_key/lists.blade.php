@extends('admin.layouts.base')
@section("styles")
    <style>
        .form-inline .form-group{
            margin-left:20px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <section class="panel">
                <header class="panel-heading">
                    <div class="clearfix">
                        搜索词管理
                        <div class="btn-group pull-right">
                            <a role="button" class="btn btn-primary" href="javascript:;">
                                搜索词列表
                            </a>
                        </div>
                    </div>
                </header>

                <div class="panel-body">
                    <label class="pull-left">
                        信息总量：{{$lists->total()}}
                    </label>

                    <form class="form-inline pull-right">


                        <div class="form-group">
                            <label>名称</label>
                            <input type="text" class="form-control" name="keyword" value="{{request('keyword')}}">
                        </div>


                        <div class="form-group">
                            <button class="btn btn-search" type="submit"><i class="fa fa-search-minus"></i> 搜索</button>
                        </div>

                    </form>
                </div>

                <div class="panel-body">

                    <div class="adv-table">

                        <table aria-describedby="editable-sample_info" class="table table-striped table-hover table-bordered dataTable">
                            <thead>
                            <tr>
                                <th>搜索词</th>
                                <th>分词后结果</th>
                                <th>类型</th>
                                <th>链接地址</th>
                                <th>次数</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($lists))
                                @foreach($lists as $item)
                                    <tr>
                                        <th>{{$item['keyword']}}</th>
                                        <th>{{implode(',',array_unique(explode(',',$item['scws_str'] ?? '无')))}}</th>
                                        <th>{{['1'=>'商品','2'=>'需求'][$item['object_type']] ?? ''}}</th>
                                        <th>{!! to_route('search',['keyword'=>$item['keyword']]) !!}</th>
                                        <th>{{$item['total']}}</th>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="dataTables_info" id="editable-sample_info"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="dataTables_paginate paging_bootstrap pagination">
                                    {!! $lists->links('admin.layouts.page_html') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection