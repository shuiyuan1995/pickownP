@extends('admin.layouts.iframe')
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <form action="" class="form-horizontal" autocomplete="off">
                <div class="form-group">
                    <div class="col-md-2 control-label">关键字</div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="key" value="{{request('key')}}" placeholder="用户名字">
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
                    <th>浏览用户</th>
                    <th>站内信标题</th>
                    <th>查看时间</th>
                </tr>
                </thead>
                <tbody>
                @foreach($list as $item)
                    <tr>
                        <td>
                            {{$item->getUser->name}}
                        </td>
                        <td>
                            {{$item->getMsg->title}}
                        </td>
                        <td>
                            {{$item->created_at}}
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