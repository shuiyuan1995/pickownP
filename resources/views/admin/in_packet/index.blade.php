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
                    <th>红包ID</th>
                    <th>抢红包用户名</th>
                    <th>抢的金额</th>
                    <th>抢红包的尾号</th>
                    <th>输赢</th>
                    <th>状态</th>
                    <th>更新时间</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($list as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->outid }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->sum }}</td>
                        <td>{{ $item->packet_tail_number }}</td>
                        <td>{{ $item->is_win == 1 ? '中奖':'未中奖' }}</td>
                        <td>{{ $item->status == 1 ? '正常' : '异常' }}</td>
                        <td>{{ $item->updated_at }}</td>
                        <td>{{ $item->created_at }}</td>
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
