@extends('admin.layouts.iframe')
@section('content')
    {{dd($aa)}}
    <div class="box">
        <div class="box-header with-border">
            <form action="" class="form-horizontal" autocomplete="off">
                <div class="form-group">
                    <div class="col-md-2 control-label">开始时间</div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="start_time" value="{{request('start_time')}}" placeholder="开始时间">
                    </div>
                    <div class="col-md-2 control-label">结束时间</div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="end_time" value="{{request('end_time')}}" placeholder="结束时间">
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
                    <th>用户名</th>
                    <th>收益</th>
                </tr>
                </thead>
                <tbody>
                @foreach($list as $item)
                    <tr>
        
                        <td>
                            {{$item->name}}
                        </td>
                        <td>
                            {{$item->num}}
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