@extends('admin.layouts.iframe')
@section('content')
<div class="box box-info">
    <div class="box-header with-border">
        <a href="{{route('admin.game_partition.index')}}" class="btn btn-default"> 返回</a>
    </div>

    <div class="box-body">
        <form action="{{route('admin.game_partition.update',$entity)}}" class="form-horizontal validate" method="post">
            {{csrf_field()}}
            {{method_field('put')}}
            <div class="form-group">
                <label for="inputKey" class="control-label col-md-2">分区名称</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="name" value="{{ $entity->name }}" id="inputName" data-rule-required="true" data-rule-remote="{{route('api.web.unique',['table'=>'game_partitions', 'unique'=>'name', 'ignore'=>$entity->name])}}">
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="control-label col-md-2">分区金额*</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="sum" value="{{ $entity->sum / 10000 }}" id="inputSum" data-rule-required="true" placeholder="填写整数" title="填写整数">
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="control-label col-md-2">概率上限*</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="up" value="{{ $entity->up / 100 }}" id="inputUp" data-rule-required="true" placeholder="直接填写数字，最多两位小数点" title="直接填写数字，最多两位小数点">
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="control-label col-md-2">概率下限*</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="down" value="{{ $entity->down / 100 }}" id="inputDown" data-rule-required="true" placeholder="直接填写数字，最多两位小数点" title="直接填写数字，最多两位小数点">
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="control-label col-md-2">默认尾数*</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="number" value="{{ $entity->number }}" id="inputNumber" data-rule-required="true" placeholder="填写一位整数" title="填写一位整数">
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="control-label col-md-2">默认个数*</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="count" value="{{ $entity->count }}" id="inputCount" data-rule-required="true" placeholder="填写一位整数" title="填写一位整数">
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="control-label col-md-2">分区状态</label>
                <div class="col-md-8">
                    <label><input type="radio" class="radio-inline" name="status" id="inputStatus" value="1" @if($entity->status == 1) checked @endif>开启</label>&nbsp;&nbsp;
                    <label><input type="radio" class="radio-inline" name="status" id="inputStatus" value="2" @if($entity->status == 2) checked @endif>关闭</label>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-2 col-md-2">
                    <button type="submit" class="btn btn-primary">提交</button>
                    <a href="{{route('admin.game_partition.index')}}" class="btn btn-default"> 返回</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection