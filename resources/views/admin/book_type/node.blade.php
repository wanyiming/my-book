<ol class="dd-list">
@foreach($bookType as $c)
    <li class="dd-item node-operation ">
        <div class="dd-not-handle">{{$c['name']}}
            <div class="btn-group pull-right">
                <a class="btn btn-xs text-info" href="{{to_route('admin.book_type.edit',['id'=>$c['id']])}}"><i class="fa fa-edit"></i>编辑</a>
                <a class="btn btn-xs text-muted ajax-post-node-delete" href="{{to_route('admin.book_type.del',['id'=>$c['id']])}}"><i class="fa fa-trash-o"></i>删除</a>
            </div>
        </div>
    </li>
@endforeach
</ol>
