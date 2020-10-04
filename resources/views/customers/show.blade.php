@extends('layouts.app')
@section('content')
    <div class="user">
        <div class="row">
            <div class="col-xs-6 col-xs-xs-12">
                <div class="user__title">
                    <h2>{{$customer->fullname}}</h2>
                    @if($customer->birthday)
                        <p>{{$customer->birthday}}</p>
                    @endif
                    @if($customer->phone)
                        <p>{{$customer->phone}}</p>
                    @endif
                    @if($customer->email)
                        <p>{{$customer->email}}</p>
                    @endif
                    <p>
                        <span>@lang('site.discount'):бар - {{$customer->skidkabarnumber}}% @lang('site.bill') - {{$customer->skidkabilnumber}}%</span>
                    </p>
                </div>
            </div>
            <div class="col-xs-6 col-xs-xs-12">
                <div class="user__info">
                    <div class="user__info-item blue__bg">
                        <p>@lang('site.summaAll')</p>
                        <img src="/img/sum.png" alt="sum">
                        <span>{{$ordersum}} ₴</span>
                    </div>
                    <div class="user__info-item orange__bg">
                        <p>@lang('site.skidkaAll')</p>
                        <img src="/img/discount.png" alt="discount">
                        <span>0 ₴</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="user_table">
            <div class="user_top">
                <delete-customer id="{{$customer->id}}"></delete-customer>
                <a class="edit modalShow" href="#">@lang('site.edit')</a>
            </div>
            @if($customer->orders)
                <table>
                    <tr class="td-one">
                        <td>#</td>
                        <td>@lang('site.typeText')</td>
                        <td>@lang('site.dateStartText')</td>
                        <td>@lang('site.dateEndText')</td>
                        <td>@lang('site.summaAll')</td>
                    </tr>
                    @foreach($customer->orders as $order)
                        <tr>
                            <td>{{$order->id}}</td>
                            <td>{{$order->type}}</td>
                            <td>{{ \Carbon\Carbon::parse($order->start)->format('d-m-Y (H:i)') }}</td>
                            <td>
                                @if($order->closed)
                                    {{ \Carbon\Carbon::parse($order->closed)->format('d-m-Y (H:i)') }}
                                @endif
                            </td>
                            <td>{{$order->amount}} ₴</td>
                        </tr>
                    @endforeach
                </table>

            @endif
        </div>
    </div>
    <div class="win" id="readOrderWin">
        <div class="form-user">
            <div class="row">
                <div class="col-xs-8 col-sm-6 col-sm-push-6">
                    <p class="p">
                        <span class="h4">@lang('site.edit')</span>
                    </p>
                </div>
                <div class="col-xs-4 col-sm-6 col-sm-pull-6">
                    <p><span>ID : {{$customer->id}}</span></p>
                </div>
            </div>
            <read-customer id="{{$customer->id}}"></read-customer>
        </div>
        <a class="close " id="closeCompareActWin" title="Закрыть " href="#"></a>
    </div>
@endsection