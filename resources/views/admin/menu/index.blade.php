@extends('admin.layouts.iframe')
@section('content')
    <div class="box box-info">
        <div class="box-header with-border">
            @can('admin.menu.create')
            <a href="{{route('admin.menu.create')}}" class="btn btn-default"><i class="fa fa-plus"></i> 添加</a>
            @endcan
        </div>
        <div class="box-body row">
            <div class="col-md-6" onselectstart="return false;">
                @component('admin.menu.tree', ['permissions'=>$list, 'pid'=>0])
                @endcomponent
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('.collapse').on('hide.bs.collapse', function () {
            var id = $(this).attr('id')
            var element = $('[data-target="#'+id+'"]')
            element.find('.fa.pull-right').addClass('fa-angle-left').removeClass('fa-angle-down');
        }).on('show.bs.collapse', function () {
            var id = $(this).attr('id')
            var element = $('[data-target="#'+id+'"]')
            element.find('.fa.pull-right').addClass('fa-angle-down').removeClass('fa-angle-left');
        })
    </script>
@endsection
