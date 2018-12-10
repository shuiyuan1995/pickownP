@extends('admin.layouts.iframe')
@section('content')
<div class="box box-info">
    <div class="box-header with-border">
        <a href="{{route('admin.home_user.index')}}" class="btn btn-default"> 返回</a>
    </div>

    <div class="box-body">
        <form action="{{route('admin.home_user.update',$entity)}}" class="form-horizontal validate" method="post">
            {{csrf_field()}}
            {{method_field('put')}}
            <div class="form-group">
                <label for="inputKey" class="control-label col-md-2">用户名</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="name" value="{{ $entity->name }}" id="inputName" readonly>
                </div>
            </div>
            <div class="form-group">
                <label for="inputKey" class="control-label col-md-2">公钥</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="walletid" value="{{ $entity->publickey }}" id="inputWalletid" readonly>
                </div>
            </div>

            <div class="form-group">
                <label for="inputName" class="control-label col-md-2">用户状态</label>
                <div class="col-md-8">
                    <label><input type="radio" class="radio-inline" name="status" id="inputStatus" value="1" @if($entity->status == 1) checked @endif>开启</label>&nbsp;&nbsp;
                    <label><input type="radio" class="radio-inline" name="status" id="inputStatus" value="2" @if($entity->status == 2) checked @endif>关闭</label>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-2 col-md-2">
                    <button type="submit" class="btn btn-primary">提交</button>
                    <a href="{{route('admin.home_user.index')}}" class="btn btn-default"> 返回</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection