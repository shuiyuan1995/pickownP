@extends('admin.layouts.iframe')
@section('content')

    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="https://eospark.com/account/hongbaogames" title="点击查看账户交易详情" target="_blank">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-btc"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">合约账户余额</span>
                        <span class="info-box-number">{{ $contract }}
                            <small>EOS</small></span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="https://eospark.com/account/hongbaogames" title="点击查看账户交易详情" target="_blank">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-500px"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">营收账户余额</span>
                        <span class="info-box-number">{{ $revenue }}
                            <small>EOS</small></span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="https://eospark.com/account/hongbaogames" title="点击查看账户交易详情" target="_blank">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-eur"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">奖励账户余额</span>
                        <span class="info-box-number">{{ $reward }}
                            <small>EOS</small></span>
                    </div>
                </div>
            </a>
        </div>

    </div>
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="https://eospark.com/account/hongbaogames" title="点击查看账户交易详情" target="_blank">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-try"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">空投账户余额</span>
                        <span class="info-box-number">{{ $mining }}
                            <small>EOS</small></span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="https://eospark.com/account/hongbaogames" title="点击查看账户交易详情" target="_blank">
                <div class="info-box">
                    <span class="info-box-icon bg-purple"><i class="fa fa-usd"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">挖矿账户余额</span>
                        <span class="info-box-number">{{ $airdrop }}
                            <small>EOS</small></span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="https://eospark.com/account/hongbaogames" title="点击查看账户交易详情" target="_blank">
                <div class="info-box">
                    <span class="info-box-icon bg-blue"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">分红账户余额</span>
                        <span class="info-box-number">{{ $fenhong }}
                            <small>EOS</small></span>
                    </div>
                </div>
            </a>
        </div>

    </div>
@endsection
@section('script')

@endsection
