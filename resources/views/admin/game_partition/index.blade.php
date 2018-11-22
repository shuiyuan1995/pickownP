@extends('admin.layouts.iframe')
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <form action="" class="form-horizontal" autocomplete="off">
                <div class="form-group">
                    <div class="col-md-2 control-label">分区名</div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="key" value="{{request('name')}}"
                               placeholder="分区名">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-4 pull-right">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                        <a href="{{route('admin.game_partition.create')}}" class="btn btn-default"><i class="fa fa-plus"></i>
                            添加</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="box-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>分区名</th>
                    <th>分区金额</th>
                    <th>概率上限</th>
                    <th>概率下限</th>
                    <th>默认尾数</th>
                    <th>分区状态</th>
                    <td>更新时间</td>
                    <td>编辑</td>
                </tr>
                </thead>
                <tbody>
                @foreach($list as $item)
                    <tr>
                        <th>{{ $item->name }}</th>
                        <th>{{ $item->sum / 10000 }}</th>
                        <th>{{ $item->up / 100 }}%</th>
                        <th>{{ $item->down / 100 }}%</th>
                        <th>{{ $item->number }}</th>
                        <th>{{ $item->status == 1 ? '开启':'关闭' }}</th>
                        <td>{{ $item->updated_at }}</td>
                        <td>
                            <a href="{{route('admin.game_partition.edit', $item)}}" class="btn btn-info btn-sm">修改</a>
                            <button type="submit" form="delForm{{$item->id}}" class="btn btn-default btn-sm" title="删除"
                                    onclick="return confirm('是否确定？')">删除
                            </button>
                            <form class="form-inline hide" id="delForm{{ $item->id }}"
                                  action="{{ route('admin.game_partition.destroy', $item) }}" method="post">
                                {{ csrf_field() }} {{ method_field('DELETE') }}
                            </form>
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
