@extends('admin.layouts.iframe')
@section('content')
<div class="box box-info">
    <div class="box-header with-border">
        <a href="{{route('admin.site_mails.index')}}" class="btn btn-default"> 返回</a>
    </div>

    <div class="box-body">
        <form action="" class="form-horizontal validate" method="post">
            <div class="form-group">
                <label for="inputKey" class="control-label col-md-2">标题*</label>
                <div class="col-md-8">
                <input type="text" class="form-control" name="title" id="inputTitle" value="{{$site_mail->title}}">
                </div>
            </div>

            <div class="form-group">
                <label for="" class="col-md-2 control-label">类容</label>
                <div class="col-md-8">
                    <textarea name="content" class="form-control" data-rule-required="true">{{$site_mail->content}}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-md-2 control-label">状态</label>
                <div class="col-md-8">
                    <label class="radio-inline"><input name="status" type="radio" value="2" <?= $site_mail->status == 2 ? 'checked' : '';?> />发布 </label>
                    <label class="radio-inline"><input name="status" type="radio" value="1" <?= $site_mail->status == 1 ? 'checked' : '';?> />草稿 </label>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection