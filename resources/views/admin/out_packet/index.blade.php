@extends('admin.layouts.iframe')
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <form action="" class="form-horizontal" autocomplete="off">
                <div class="form-group">
                    <div class="col-md-2 control-label">请输入</div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="name" value="{{request('name')}}"
                               placeholder="用户名">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="number" value="{{request('number')}}"
                               placeholder="尾数">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="eosid" value="{{request('eosid')}}"
                               placeholder="eosid">
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="status" title="红包是否领完">
                            <option value="">是否领完</option>
                            @foreach($statusArr as $item => $value)
                                <option value="{{$item}}"
                                        @if($item == request('status'))selected @endif>{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="index" title="红包金额">
                            <option value="">请选择红包金额</option>
                            @foreach($indexArr as $item => $value)
                                <option value="{{$item}}"
                                        @if($item == request('index'))selected @endif>{{$item}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-2 control-label">时间范围</div>
                    <div class="col-md-2">
                        <input type="text" class="form-control datetime" name="begin_time"
                               value="{{request('begin_time')}}"
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
                    <th>ID</th>
                    <th>用户名</th>
                    <th>尾数</th>
                    <th>发出总额</th>
                    <th>总个数</th>
                    <th>区块链ID</th>
                    <th>blocknumber</th>
                    <th>状态</th>
                    <th>更新时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($list as $item)

                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->tail_number }}</td>
                        <td>{{ $item->issus_sum }}</td>
                        <td>{{ $item->count }}</td>
                        <td>{{ $item->eosid }}</td>
                        <td><a target="_blank" title="点击查看详情" href="https://eospark.com/tx/{{ $item->blocknumber }}" class="btn">{{ $item->blocknumber }}</a> </td>
                        <td>{{ $item->statusArr[$item->status] }}</td>
                        <td>{{ $item->updated_at }}</td>
                        <td>
                            <a href="{{route('admin.out_packet.edit', $item)}}" class="btn btn-info btn-sm">修改</a>
                            <a href="{{ route('admin.in_packet.index',['id'=>$item->id]) }}"
                               class="btn btn-default btn-sm">查看红包领取记录</a></td>
                    </tr>

                @endforeach
                </tbody>
            </table>
        </div>
        <div class="box-footer">
            当前页共计{{count($list)}}
        </div>
        <div class="box-footer clearfix">
            {{$list->appends(request()->all())->links()}}
        </div>
    </div>
@endsection
@section('script')

@endsection
