@extends('admin.layouts.iframe')
@section('content')
<div class="box box-info">
    <div class="box-header with-border">
        <a href="{{route('admin.ad_managments.index')}}" class="btn btn-default"> 返回</a>
    </div>

    <div class="box-body">
        <form action="{{route('admin.ad_managments.update',$ad_managments)}}" class="form-horizontal validate" method="post">
            {{csrf_field()}}
            {{method_field('put')}}
            <div class="form-group">
                <label for="inputType" class="control-label col-md-2">广告位*</label>
                <div class="col-md-8">
                    <select name="ad_id" id="inputType" class="form-control select2" data-rule-required="true" data-json="{{json_encode($ad_positions)}}" data-ajax-url="{{route('api.web.adpositions')}}">
                    </select>
                    <input type="hidden" name="type" id="type" value="{{$ad_managments->type}}">
                </div>
            </div>
            <div class="form-group">
                <label for="inputKey" class="control-label col-md-2">广告名称*</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" value="{{$ad_managments->name}}" name="name" id="inputName" data-rule-required="true">
                </div>
            </div>
            <div class="form-group" <?= ($ad_managments->type ==3) ?'style="display:none;"':'';?>>
                <label for="" class="col-md-2 control-label">文件</label>
                <div class="col-md-8">
                    <input type="file" name="img_url"  class="form-control file-input" data-initial-preview="{{\Storage::url($ad_managments->img_url)}}">
                </div>
            </div>
            <div class="form-group img2" >
                <label for="" class="col-md-2 control-label">内容</label>
                <div class="col-md-8">
                <script id="container" name="img_url_text" type="text/plain">{!! $ad_managments->img_url !!}</script>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-md-2 control-label">排序</label>
                <div class="col-md-8">
                <input type="number" data-rule-number="true" data-rule-decimal="0" data-msg-decimal="必须是正整数" min="0" max="9999" class="form-control" name="sort" value="0">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-2">
                    <button type="submit" class="btn btn-primary">提交</button>
                    <a href="javascript:history.back()" class="btn btn-default"> 返回</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@include('vendor.ueditor.assets')
@section('script')
<script>
$(function () {
    var ue = UE.getEditor('container');console.log(ue);
    ue.ready(function() {
        ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
    });
    $('#inputType').on('select2:select', function (e) {
        var data = e.params.data;
        $("#type").val(data.type);
        if (data.type == 3) {
            $(".img2").show();
            $(".img1").hide();
        } else {
            $(".img2").hide();
            $(".img1").show();
        }
    });
});
</script>
@endsection