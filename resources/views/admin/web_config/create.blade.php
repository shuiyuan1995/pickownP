@extends('admin.layouts.iframe')
@section('content')
<div class="box box-info">
    <div class="box-header with-border">
        <a href="javascript:history.back()" class="btn btn-default"> 返回</a>
    </div>

    <div class="box-body">
        <form action="{{route('admin.web_config.store')}}" class="form-horizontal validate" method="post">
            {{csrf_field()}}
            <div class="form-group">
                <label for="inputKey" class="control-label col-md-2">配置key*</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="key" id="inputKey" data-rule-required="true">
                </div>
            </div>
            <div class="form-group">
                <label for="inputKey" class="control-label col-md-2">配置名称*</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="name" id="inputName" data-rule-required="true">
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-md-2 control-label">配置内容*</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="content" data-rule-required="true">
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