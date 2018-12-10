@extends('admin.layouts.iframe')
@section('content')
    <div class="box-header with-border">
        <form action="" class="form-horizontal" autocomplete="off">
              <div class="form-group">
                  <div class="col-md-2 control-label">开始时间</div>
                  <div class="col-md-2">
                      <input type="text" class="form-control datetime" name="start_time" value="{{$start_time}}" placeholder="开始时间">
                  </div>
                  <div class="col-md-2 control-label">结束时间</div>
                  <div class="col-md-2">
                      <input type="text" class="form-control datetime" name="end_time" value="{{$end_time}}" placeholder="结束时间">
                  </div>
              </div>
              <div class="form-group">
                  <div class="col-md-1 pull-left">
                      <button type="submit" class="btn btn-primary time" name="hour" value="1"><i class="fa fa-search"></i> 24小时</button>
                  </div>
                  <div class="col-md-1 pull-left">
                      <button type="submit" class="btn btn-primary time" name="three_day" value="3"><i class="fa fa-search"></i> 3天</button>
                  </div>
                  <div class="col-md-1 pull-left">
                      <button type="submit" class="btn btn-primary time" name="day" value="7"><i class="fa fa-search"></i> 7天</button>
                  </div>
                  <div class="col-md-6 pull-left">
                      <button type="submit" class="btn btn-primary time" name="month" value="1"><i class="fa fa-search"></i> 一个月</button>
                  </div>
                  <div class="col-md-1 pull-left">
                      <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                  </div>
              </div>
        </form>
    </div>
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-user"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">注册用户</span>
              <span class="info-box-number">{{$users_count}}</span>
            </div>
          </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-user-plus"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">付费用户</span>
              <span class="info-box-number">{{$paying_count}}</span>
            </div>
          </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">活跃用户</span>
              <span class="info-box-number">{{$active_users_count[0]->num}}</span>
            </div>
          </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-bars"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">发红包数</span>
              <span class="info-box-number">{{$red_bag_num}}</span>
            </div>
          </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-purple"><i class="fa fa-jpy"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">交易额</span>
              <span class="info-box-number">{{$money}}<small>EOS</small></span>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-blue"><i class="fa fa-amazon"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">留存</span>
                    <span class="info-box-number">{{$liucun}}<small>EOS</small></span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-balance-scale"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">时间段内的交易数</span>
                    <span class="info-box-number">{{$fenbu}}</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-firefox"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">新增用户量</span>
                    <span class="info-box-number">{{$xinzeng}}<small>EOS</small></span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-opera"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">活跃度</span>
                    <span class="info-box-number">{{$huoyedu}}<small>%</small></span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-chrome"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">平均每活跃用户收益</span>
                    <span class="info-box-number">{{$pingjunmei}}</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-purple"><i class="fa fa-get-pocket"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">每付费用户平均收益</span>
                    <span class="info-box-number">{{$meifufei}}<small>EOS</small></span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-blue"><i class="fa fa-gg"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">付费率</span>
                    <span class="info-box-number">{{$fufeilv}}<small>%</small></span>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
