@if($nodes->where('parent_id',$parentId)->first())
<ol class="dd-list">
@foreach($nodes->where('parent_id',$parentId) as $key => $node)
    <li class="dd-item @if(substr_count($node['tree'],',') >= 3) node-operation @endif">
        <div class="dd-not-handle" @if(substr_count($node['tree'],',') == 2) style="color: darkred;" @endif>{{$node['title']}}
            <div class="btn-group pull-right">
                @if(substr_count($node['tree'],',') < 3)
                <a class="btn btn-xs text-danger" href="{{to_route('admin.node.add',['parent_id'=>$node['id']])}}"><i class="fa fa-plus"></i>@if(substr_count($node['tree'],',') == 2)操作@else添加节点@endif</a>
                @endif
                <a class="btn btn-xs text-info" href="{{to_route('admin.node.edit',['id'=>$node['id']])}}"><i class="fa fa-edit"></i>编辑</a>
                <a class="btn btn-xs text-muted ajax-post-node-delete" href="{{to_route('admin.node.delete',['id'=>$node['id']])}}"><i class="fa fa-trash-o"></i>删除</a>
            </div>
        </div>
        {!! view('admin.node.node')->with(['nodes'=>$nodes,'parentId'=>$node['id']]) !!}
    </li>
@endforeach
</ol>
@endif