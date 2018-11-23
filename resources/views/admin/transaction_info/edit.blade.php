@extends('admin.layouts.iframe')
@section('content')
<div class="box box-info">
    <div class="box-header with-border">
        <a href="{{route('admin.transaction_info.index')}}" class="btn btn-default"> 返回</a>
    </div>

    <div class="box-body">
        <form action="{{route('admin.transaction_info.update',$entity)}}" class="form-horizontal validate" method="post">
            {{csrf_field()}}
            {{method_field('put')}}

            <div class="form-group">
                <label for="inputName" class="control-label col-md-2">备注信息</label>
                <div class="col-md-8">
                    <textarea class="form-control" name="msg" id="inputMsg" data-rule-required="true" placeholder="备注，最多225字" title="备注，最多225字">{{ $entity->count }}</textarea>
                </div>
            </div>
            @if($entity->status > 2)
            <div class="form-group">
                <label for="inputName" class="control-label col-md-2">状态</label>
                <div class="col-md-8">
                    <label><input type="radio" class="radio-inline" name="status" id="inputStatus" value="1" @if($entity->status == 3) checked @endif>异常</label>&nbsp;&nbsp;
                    <label><input type="radio" class="radio-inline" name="status" id="inputStatus" value="2" @if($entity->status == 4) checked @endif>后台修改</label>
                </div>
            </div>
            @endif
            <div class="form-group">
                <div class="col-md-offset-2 col-md-2">
                    <button type="submit" class="btn btn-primary">提交</button>
                    <a href="{{route('admin.transaction_info.index')}}" class="btn btn-default"> 返回</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection