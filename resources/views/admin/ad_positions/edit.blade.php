@extends('admin.layouts.iframe')
@section('content')
<div class="box box-info">
    <div class="box-header with-border">
        <a href="{{route('admin.ad_positions.index')}}" class="btn btn-default"> 返回</a>
    </div>

    <div class="box-body">
        <form action="{{route('admin.ad_positions.update',$ad_position)}}" class="form-horizontal validate" method="post">
            {{csrf_field()}}
            {{method_field('put')}}
            <div class="form-group">
                <label for="inputName" class="control-label col-md-2">名称*</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="name" id="inputName" value="{{$ad_position->name}}" data-rule-required="true" >
                </div>
            </div>
            <div class="form-group">
                <label for="inputType" class="control-label col-md-2">类型*</label>
                <div class="col-md-8">
                    <select name="type" id="selectType" class="form-control" data-rule-required="true">
                        <option value="1" <?=$ad_position->type==1?'selected':''?>>图片</option>
                        <option value="2" <?=$ad_position->type==2?'selected':''?> >视频</option>
                        <option value="3" <?=$ad_position->type==3?'selected':''?> >文字</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="inputType" class="control-label col-md-2">是否启用*</label>
                <div class="col-md-8">
                    <label class="radio-inline"><input name="is_use" type="radio" value="1" @if ($ad_position->is_use==1) checked @endif />是 </label>
                    <label class="radio-inline"><input name="is_use" type="radio" value="0" @if ($ad_position->is_use==0) checked @endif />否 </label>
                </div>
            </div>
            <div class="form-group">
                <label for="inputType" class="control-label col-md-2">轮播个数 </label>
                <div class="col-md-8">
                    <input type="number" name="num" data-rule-number="true" data-rule-decimal="0" min="0" data-msg-decimal="必须是正整数" class="form-control" value="{{$ad_position->num}}">
                    <p class="help-block">0为不轮播，大于0为轮播个数</p>
                </div>
            </div>
            <div class="form-group">
                <label for="inputType"  class="control-label col-md-2">简绍*</label>
                <div class="col-md-8">
                <textarea name="intro" class="form-control">{{$ad_position->intro}}</textarea>
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