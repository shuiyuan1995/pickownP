@extends('admin.layouts.iframe')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3>配置信息</h3>

                        <div class="col-md-4 pull-right">
                            <a href="{{route('admin.web_config.create')}}" class="btn btn-default"><i class="fa fa-plus"></i> 添加</a>
                        </div>
                </div>
                <div class="box-body">
                    <form action="" class="form-horizontal" role="form">
                        @foreach($web_config as $item)
                        <div class="form-group">
                            <label for="" class="col-md-2 control-label">{{$item->name}}</label>
                            <div class="col-md-6">
                                <input type="text" name="{{$item->id}}" class="form-control" value="{{$item->content}}">
                            </div>
                            <div class="col-md-1">
                            <a href="{{route('admin.web_config.edit',$item)}}" class="btn btn-default">修改</a>
                            </div>
                            <div class="col-md-1">
                                <a href="{{route('admin.web_config.destroy',$item)}}" class="btn btn-default">删除</a>
                            </div>
                        </div>
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection