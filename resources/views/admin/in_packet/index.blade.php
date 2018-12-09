@extends('admin.layouts.iframe')
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <form action="" class="form-horizontal" autocomplete="off">
                <div class="form-group">
                    <div class="col-md-2 control-label">请输入</div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="id" value="{{request('id')}}"
                               placeholder="发红包ID">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="name" value="{{request('name')}}"
                               placeholder="用户名">
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="ischailei" title="是否踩雷">
                            <option value="">是否踩雷</option>
                            @foreach($isChaiLeiArr as $item => $value)
                                <option value="{{ $item }}"
                                        @if($item == request('ischailei')) selected @endif >{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">

                        <select class="form-control" name="iszhongjiang" title="选中未中奖时，后面的中奖类型将不算">
                            <option value="">是否中奖</option>
                            @foreach($isRewardArr as $item => $value)
                                <option value="{{ $item }}"
                                        @if($item == request('iszhongjiang')) selected @endif>{{ $value }}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="zhongjiangtype" title="中奖类型">
                            @foreach($rewardTypeArr as $itemm => $value)
                                <option value="{{ $itemm }}"
                                        @if($itemm == request('zhongjiangtype')) selected @endif>{{ $value }}</option>
                            @endforeach
                        </select>

                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-2 control-label">时间范围</div>
                    <div class="col-md-2">
                        <input type="text" class="form-control datetime" name="begin_time" value="{{request('begin_time')}}"
                               placeholder="开始时间">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control datetime" name="end_time" value="{{request('end_time')}}"
                               placeholder="结束时间">
                    </div>
                    <div class="col-md-6 pull-right">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="box-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>

                    <th>发红包ID</th>
                    <th>抢红包用户名</th>
                    <th>抢的金额</th>
                    <th>区块链ID</th>
                    <th>blocknumber</th>
                    <th>是否踩雷</th>
                    <th>是否中奖</th>
                    <th>中奖类型</th>
                    <th>中奖金额</th>
                    <th>挖矿数</th>
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
                        <td>{{ empty($item->own)?0:$item->own }}</td>
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
