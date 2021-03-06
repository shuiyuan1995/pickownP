<div class="collapse {{$pid==0?'show':''}}" id="collapseExample{{$pid}}">
    <ul class="list-group" style="margin-bottom: 0;padding-left: {{$pid==0?'':'3'}}rem;">
        @foreach($permissions as $permission)
            @if($permission->pid == $pid)
                <li class="list-group-item" data-toggle="collapse" data-target="#collapseExample{{$permission->id}}" aria-controls="collapseExample{{$permission->id}}">
                    <i class="{{$permission->key}}"></i>
                    <a class="margin-r-5" href="{{route('admin.permission.edit', $permission)}}" title="修改">{{$permission->display_name}}</a>
                    @if(!$permission->pid)
                        <i class="fa fa-angle-left pull-right"></i>
                    @endif
                </li>
                @component('admin.user.permission_tree', ['permissions'=>$permissions, 'pid'=>$permission->id])
                @endcomponent
            @endif
        @endforeach
    </ul>
</div>