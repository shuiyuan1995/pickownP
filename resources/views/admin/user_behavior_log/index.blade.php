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
                    <th>用户名</th>
                    <th>类型</th>
                    <th>创建时间</th>
                    <th>更新时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($list as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->typeArr[$item->type] }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->updated_at }}</td>
                        <td>
                            <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-url="{{route('admin.ubi.show', $item)}}"
                                    data-target="#exampleModal" data-whatever="@mdo">查看详情
                            </button>
                            {{--<a href="{{route('admin.ubi.edit', $item)}}" class="btn btn-info btn-sm">修改</a>--}}
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
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">信息</h4>
                </div>
                <div class="modal-body" id="model-body">
                    <label class="control-label">用户名:</label>
                    <p id="user">...</p>
                    <h1 class="page-header"></h1>
                    <label class="control-label">类型:</label>
                    <p id="type">...</p>
                    <h1 class="page-header"></h1>
                    <label class="control-label">信息:</label>
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
                    modal.find('#user').text(eneity.user);
                    modal.find('#type').text(eneity.type_value);
                    modal.find('#msg').text(eneity.msg);
                    modal.find('#created_at').text(eneity.created_at.date);
                    modal.find('#updated_at').text(eneity.updated_at.date);
                }
            });
        })
    </script>
@endsection
