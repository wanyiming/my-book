@if($nodes->where('parent_id',$parentId)->first())
    <div class="panel">
        @foreach($nodes->where('parent_id',$parentId) as $key => $node)
            <div class="panel-body @if(substr_count($node['tree'],',') >= 3) panel-item @endif">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="node[]" value="{{$node['id']}}" @if(!empty($role) && in_array($node['id'],(array)$role['authority'])) checked @endif> {{$node['title']}}
                    </label>
                </div>

            {!! view('admin.role.role_node')->with(['nodes'=>$nodes,'parentId'=>$node['id'],'role' => $role ?? []]) !!}
            </div>
        @endforeach
    </div>
@endif