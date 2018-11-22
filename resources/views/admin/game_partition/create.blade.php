@extends('admin.layouts.iframe')
@section('content')
<div class="box box-info">
    <div class="box-header with-border">
        <a href="{{route('admin.game_partition.index')}}" class="btn btn-default"> 返回</a>
    </div>

    <div class="box-body">
        <form action="{{route('admin.game_partition.store')}}" class="form-horizontal validate" method="post">
            {{csrf_field()}}
            <div class="form-group">
                <label for="inputKey" class="control-label col-md-2">分区名称</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="name" id="inputName" data-rule-required="true" data-rule-remote="{{route('api.web.unique',['table'=>'game_partitions', 'unique'=>'name'])}}">
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="control-label col-md-2">分区金额</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="sum" id="inputSum" data-rule-required="true" placeholder="填写整数" title="填写整数">
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="control-label col-md-2">概率上限</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="up" id="inputUp" data-rule-required="true" placeholder="直接填写数字，最多两位小数点" title="直接填写数字，最多两位小数点">
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="control-label col-md-2">概率下限</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="down" id="inputDown" data-rule-required="true" placeholder="直接填写数字，最多两位小数点" title="直接填写数字，最多两位小数点">
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="control-label col-md-2">默认尾数</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="number" id="inputNumber" data-rule-required="true" placeholder="填写一位整数" title="填写一位整数">
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="control-label col-md-2">默认个数</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="count" id="inputCount" data-rule-required="true" placeholder="填写一位整数" title="填写一位整数">
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="control-label col-md-2">分区状态</label>
                <div class="col-md-8">
                    <label><input type="radio" class="radio-inline" name="status" id="inputStatus" value="1" checked>开启</label>&nbsp;&nbsp;
                    <label><input type="radio" class="radio-inline" name="status" id="inputStatus" value="2" >关闭</label>
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