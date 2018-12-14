@extends('admin.layouts.iframe')
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <form action="" class="form-horizontal" autocomplete="off">
                <div class="form-group">

                    <div class="col-md-2 control-label">请输入</div>
                    {{--<div class="col-md-2">--}}
                        {{--<select class="form-control" name="user_type" title="">--}}
                            {{--@foreach($data as $i => $v)--}}
                                {{--<option value="{{$i}}" @if($i == request('user_type')) selected @endif>{{$v}}</option>--}}
                            {{--@endforeach--}}
                        {{--</select>--}}
                    {{--</div>--}}
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="user" value="{{request('user')}}"
                               placeholder="用户名">
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="type" title="">
                            <option value="">请选择类型</option>
                            @foreach($typeArr as $item => $value)
                                <option value="{{$item}}"
                                        @if($item == request('type')) selected @endif>{{$value}}</option>
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
                    {{--<th>发出用户名</th>--}}
                    <th>用户名</th>
                    <th>信息类型</th>
                    <th>状态</th>
                    <th>交易额</th>
                    <th>平台</th>
                    <th>时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($list as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        {{--<td>{{ data_get($item,'issus_user.name','无') }}</td>--}}
                        <td>{{ data_get($item,'income_user.name','无') }}</td>
                        <td>{{ $item->typeArr[$item->type] }}</td>
                        <td>{{ $item->statusArr[$item->status] }}</td>
                        <td>{{ $item->eos }}</td>
                        <td>{{ $item->addr }}</td>
                        <td>{{ $item->updated_at }}</td>
                        <td>
                            <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-url="{{route('admin.transaction_info.show', $item)}}"
                                    data-target="#exampleModal" data-whatever="@mdo">查看详情
                            </button>
                            <a href="{{route('admin.transaction_info.edit', $item)}}" class="btn btn-info btn-sm">修改</a>
                        </td>
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
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">交易信息</h4>
                </div>
                <div class="modal-body" id="model-body">
                    {{--<label class="control-label">发出用户:</label>--}}
                    {{--<p id="issus_user">...</p>--}}
                    {{--<h1 class="page-header"></h1>--}}
                    <label class="control-label">获取用户:</label>
                    <p id="income_user">...</p>
                    <h1 class="page-header"></h1>
                    <label class="control-label">交易类型:</label>
                    <p id="type">...</p>
                    <h1 class="page-header"></h1>
                    <label class="control-label">状态:</label>
                    <p id="status">...</p>
                    <h1 class="page-header"></h1>
                    <label class="control-label">交易额:</label>
                    <p id="eos">...</p>

                    <h1 class="page-header"></h1>
                    <label class="control-label">备注信息:</label>
                    <p id="msg">...</p>
                    <h1 class="page-header"></h1>
                    <label class="control-label">创建时间:</label>
                    <p id="created_at">...</p>
                    <h1 class="page-header"></h1>
                    <label class="control-label">更新时间:</label>
                    <p id="updated_at">...</p>
                    <h1 class="page-header"></h1>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('#exampleModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            @if(config('app.debug'))
                console.log(event);
            console.log(button);
                    @endif
            var url = button.data('url'); // Extract info from data-* attributes
            var modal = $(this).find('.modal-body');
            $.ajax({
                url: url,
                dataType: 'json',
                success: function (data) {
                    var eneity = data.data;
                    @if(config('app.debug'))
                        console.log(data);
                    @endif
//                    modal.find('#issus_user').text(eneity.issus_user);
                    modal.find('#income_user').text(eneity.income_user);
                    modal.find('#type').text(eneity.type_value);
                    modal.find('#status').text(eneity.status_value);
                    modal.find('#eos').text(eneity.eos);
                    modal.find('#msg').text(eneity.msg);
                    modal.find('#created_at').text(eneity.created_at.date);
                    modal.find('#updated_at').text(eneity.updated_at.date);
                }
            });
        })
    </script>
@endsection
