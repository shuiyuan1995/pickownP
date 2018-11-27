@extends('admin.layouts.iframe')
@section('content')
<div class="box box-info">
    <div class="box-header with-border">
        <a href="javascript:history.back()" class="btn btn-default"> 返回</a>
    </div>

    <div class="box-body">
        <form action="{{route('admin.site_mails.store')}}" class="form-horizontal validate" method="post" enctype ="multipart/form-data">
            {{csrf_field()}}
            <div class="form-group">
                <label for="inputKey" class="control-label col-md-2">标题*</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="title" id="inputTitle" data-rule-required="true">
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-md-2 control-label">类容*</label>
                <div class="col-md-8">
                    <textarea name="content" class="form-control" data-rule-required="true"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-md-2 control-label">状态</label>
                <div class="col-md-8">
                    <label class="radio-inline"><input name="status" type="radio" value="2" checked />发布 </label>
                    <label class="radio-inline"><input name="status" type="radio" value="1" />草稿 </label>
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