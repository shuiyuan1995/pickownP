@extends('admin.layouts.iframe')
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <form action="" class="form-horizontal" autocomplete="off">
                <div class="form-group">
                    <div class="col-md-2 control-label">请输入</div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="key" value="{{request('key')}}"
                               placeholder="用户名">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-4 pull-right">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="box-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>游戏分区名</th>
                    <th>用户名</th>
                    <th>尾数</th>
                    <th>发出总额</th>
                    <th>剩余总额</th>
                    <th>总个数</th>
                    <th>剩余个数</th>
                    <th>概率上限</th>
                    <th>概率下限</th>
                    <th>状态</th>
                    <th>创建时间</th>
                    <th>更新时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($list as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->game->name }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->number }}</td>
                        <td>{{ $item->seed_sum / 10000 }}</td>
                        <td>{{ $item->surplus_sum / 10000 }}</td>
                        <td>{{ $item->count }}</td>
                        <td>{{ $item->surplus_count }}</td>
                        <td>{{ $item->up }}</td>
                        <td>{{ $item->down }}</td>
                        <td>{{ $item->status == 1 ? '正常' : '冻结' }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->updated_at }}</td>
                        <td>
                            <a href="{{route('admin.home_user.edit', $item)}}" class="btn btn-info btn-sm">修改</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="box-footer clearfix">
            {{$list->appends(request()->all())->links()}}
        </div>
    </div>
@endsection
@section('script')

@endsection
