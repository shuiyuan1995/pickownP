@extends('admin.layouts.iframe')
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <form action="" class="form-horizontal" autocomplete="off">
                <div class="form-group">
                    <div class="col-md-4 pull-right">
                        <a href="{{route('admin.ad_managments.create')}}" class="btn btn-default"><i class="fa fa-plus"></i> 添加</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="box-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>广告位名称</th>
                    <th>广告名称</th>
                    <th>广告类型</th>
                    <th>是否显示</th>
                    <th>广告位图片</th>
                    <th>排序</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($list as $item)
                    <tr>
        
                        <td>
                            {{$item->adposition->name}}
                        </td>
                        <td>
                            {{$item->name}}
                        </td>
                        <td>
                            {{$item->adposition->type_name}}
                        </td>
                        <td>
                            {{$item->adposition->use_name}}
                        </td>
                        <td>
                        @if ($item->adposition->type == 1) 
                            <img height="200" width="200" src="{{\Storage::url($item->img_url)}}">
                        @endif
                            
                        </td>
                        <td>
                            {{$item->sort}}
                        </td>
                        <td>
                            <a href="{{route('admin.ad_managments.edit', $item)}}" class="btn btn-info btn-sm">修改</a>
                            <button type="submit" form="delForm{{$item->id}}" class="btn btn-default btn-sm" title="删除" onclick="return confirm('是否确定？')">删除</button>
                            <form class="form-inline hide" id="delForm{{$item->id}}" action="{{ route('admin.ad_managments.destroy', $item) }}" method="post">
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