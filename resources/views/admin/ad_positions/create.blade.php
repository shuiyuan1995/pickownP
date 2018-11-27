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
                    <select name="type" id="selectType" class="form-control" data-rule-required="true">
                        <option value="1" selected="selected">图片</option>
                        <option value="2" >视频</option>
                        <option value="3">文字</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="inputType" class="control-label col-md-2">是否启用*</label>
                <div class="col-md-8">
                    <label class="radio-inline"><input name="is_use" type="radio" value="1" checked />是 </label>
                    <label class="radio-inline"><input name="is_use" type="radio" value="0" />否 </label>
                </div>
            </div>
            <div class="form-group">
                <label for="inputType" class="control-label col-md-2">轮播个数 </label>
                <div class="col-md-8">
                    <input type="number" name="num" value="0"  data-rule-number="true" data-rule-decimal="0" min="0" data-msg-decimal="必须是正整数" class="form-control">
                    <p class="help-block">0为不轮播，大于0为轮播个数</p>
                </div>
            </div>
            <div class="form-group">
                <label for="inputType"  class="control-label col-md-2">简绍*</label>
                <div class="col-md-8">
                <textarea name="intro" class="form-control"></textarea>
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