@extends('admin.layouts.iframe')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3>基本表单</h3>
                </div>
                <div class="box-body">
                    <form action="" class="form-horizontal" role="form">
                        <div class="form-group">
                            <label for="" class="col-md-2 control-label">Input</label>
                            <div class="col-md-8">
                                <input type="text" name="" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-md-2 control-label">Select</label>
                            <div class="col-md-8">
                                <select name="" class="form-control">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-md-2 control-label">Select2</label>
                            <div class="col-md-8">
                                <select name="" class="form-control select2" data-ajax-url="{{route('api.web.keywords_type')}}">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-md-2 control-label">Date</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control date">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-md-2 control-label">Date Time</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control datetime">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-md-2 control-label">File</label>
                            <div class="col-md-8">
                                <input type="file" class="form-control file-input">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-md-2 control-label">File preview</label>
                            <div class="col-md-8">
                                <input type="file" class="form-control file-input" data-initial-preview="https://colorhub.me/imgsrv/HV9LmR4DgWV2f8G7AqWapN,https://colorhub.me/imgsrv/a89cyUzdPhNnzAM6vRPTaB">
                                <p class="help-block">添加属性 <b>data-initial-preview="图片地址,图片地址"</b></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8 col-lg-offset-2">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function () {
            $('.file-input').fileinput({
                // 语言
                language: 'zh',
                // 是否允许拖动文件
                dropZoneEnabled: false,
                // 是否异步上传
                uploadAsync: false,
                // 上传按钮
                showUpload: false,
                // 预览文件的操作
                fileActionSettings: {
                    // 删除按钮
                    showRemove: false,
                    // 拖动按钮
                    showDrag: false
                },
                // 浏览文件的按钮样式
                browseClass: 'btn bg-purple',
                // 初始化文件预览的分隔符
                initialPreviewDelimiter: ',',
                // 初始化文件预览的数据格式
                initialPreviewAsData: true,
                // 关闭预览区域按钮
                showClose: false,
                // 全部删除按钮
                showRemove: false,
                // 允许上传的文件类型 ['image', 'html', 'text', 'video', 'audio', 'flash', 'object']
                allowedFileTypes: ['image'],
                // 当选择的不符合规则时, 删除该文件的预览
                removeFromPreviewOnError: true
            });
        })
    </script>
@endsection