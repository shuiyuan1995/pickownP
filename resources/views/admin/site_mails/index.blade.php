@extends('admin.layouts.iframe')
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <form action="" class="form-horizontal" autocomplete="off">
                <div class="form-group">
                    <div class="col-md-4 pull-right">
                        <a href="{{route('admin.site_mails.create')}}" class="btn btn-default"><i class="fa fa-plus"></i> 添加</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="box-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>消息类型</th>
                    <th>发送人</th>
                    <th>标题</th>
                    <th>状态</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($list as $item)
                    <tr>
                        <td>
                            {{$item->typeName}}
                        </td>
                        <td>
                            {{$item->getUser->name}}
                        </td>
                        <td>
                            {{$item->title}}
                        </td>
                        <td>
                            {{$item->statusName}}
                        </td>
                        <td>
                            <a href="{{route('admin.site_mails.show', $item)}}" class="btn btn-info btn-sm">查看</a>
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