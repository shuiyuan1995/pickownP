@extends('admin.layouts.iframe')
@section('content')
<div class="box box-info">
    <div class="box-header with-border">
        <a href="javascript:history.back()" class="btn btn-default"> 返回</a>
    </div>

    <div class="box-body">
        <form action="{{route('admin.ad_managments.store')}}" class="form-horizontal validate" method="post" enctype ="multipart/form-data">
            {{csrf_field()}}
            <div class="form-group">
                <label for="inputType" class="control-label col-md-2">广告位*</label>
                <div class="col-md-8">
                    <select name="ad_id" id="inputType" class="form-control select2" data-rule-required="true" data-ajax-url="{{route('api.web.adpositions')}}">
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="inputKey" class="control-label col-md-2">广告名称*</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="name" id="inputName" data-rule-required="true">
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-md-2 control-label">文件</label>
                <div class="col-md-8">
                    <input type="file" name="img_url"   class="form-control file-input">
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
@section('script')
<script>
$(function () {
    $('#inputType').on('select2:select', function (e) {
        var data = e.params.data;
        if (data.type == 3) {
            alert(1)
        }
    });
});
</script>
@endsection