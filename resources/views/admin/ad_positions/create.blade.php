@extends('admin.layouts.iframe')
@section('content')
<div class="box box-info">
    <div class="box-header with-border">
        <a href="{{route('admin.ad_positions.index')}}" class="btn btn-default"> 返回</a>
    </div>

    <div class="box-body">
        <form action="{{route('admin.ad_positions.store')}}" class="form-horizontal validate" method="post">
            {{csrf_field()}}
            <div class="form-group">
                <label for="inputName" class="control-label col-md-2">名称*</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="name" id="inputName" data-rule-required="true">
                </div>
            </div>
            <div class="form-group">
                <label for="inputType" class="control-label col-md-2">类型*</label>
                <div class="col-md-8">
                    <!-- <input type="text" class="form-control" name="type" id="inputType" data-rule-required="true"> -->
                    <select name="type" id="Type" name="type" class="form-control">
                        <option value="1" selected="selected">图片</option>
                        <option value="2" >视频</option>
                        <option value="3">文字</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-2">
                    <button type="submit" class="btn btn-primary">提交</button>
                    <a href="{{route('admin.ad_positions.index')}}" class="btn btn-default"> 返回</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection