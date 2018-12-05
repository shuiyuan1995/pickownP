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

                    <th>红包ID</th>
                    <th>抢红包用户名</th>
                    <th>抢的金额</th>
                    <th>区块链ID</th>
                    <th>blocknumber</th>
                    <th>是否踩雷</th>
                    <th>是否中奖</th>
                    <th>中奖类型</th>
                    <th>中奖金额</th>
                    <th>时间</th>
                    {{--<th>操作</th>--}}
                </tr>
                </thead>
                <tbody>
                @foreach($list as $item)
                    <tr>

                        <td>{{ $item->outid }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->income_sum }}</td>
                        <td>{{ $item->eosid }}</td>
                        <td>{{ $item->blocknumber }}</td>
                        <td>{{ $item->is_chailei_arr[$item->is_chailei] }}</td>
                        <td>{{ $item->is_reward_arr[$item->is_reward] }}</td>
                        <td>{{ $item->rewardTypeArr[$item->reward_type] }}</td>
                        <td>{{ $item->reward_sum }}</td>
                        <td>{{ $item->updated_at }}</td>
                        {{--<td>--}}
                            {{--<a href="{{route('admin.home_user.edit', $item)}}" class="btn btn-info btn-sm">修改</a>--}}
                        {{--</td>--}}
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
