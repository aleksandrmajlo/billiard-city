@extends('layouts.app')
@section('content')
    <div class="user">

        <div class="user__title">
            <h2>@lang('site.zakaz')</h2>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-7">
                <div class="filter-block filter-order">
                    <form class="filter-form" >
                        <div class="row">
                            <div class="col-xs-3 col-xs-xs-6">
                                <label>Номер</label>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <p>№{{ $id }}</p>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <label>@lang('orders.client')</label>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <p class="text-nowrap">
                                    @if($order->customer)
                                        {{$order->customer->fullname}}
                                    @else
                                        @lang('orders.guest')
                                    @endif
                                </p>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6"><label>@lang('orders.start')</label></div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <p class="text-nowrap">{{$order->start}}</p>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6"><label>@lang('orders.discount')</label></div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <p class="text-nowrap">
                                    @if($order->customer&&$order->customer->skidka)
                                        {{$order->customer->skidka}}%
                                    @else
                                        0
                                    @endif
                                </p>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <label>@lang('orders.end')</label>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <p class="text-nowrap">{{$order->closed}}</p>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <label>@lang('orders.oplata')</label>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                @if($order->billing)
                                    <p class="text-nowrap">
                                        {{$billing[$order->billing][app()->getLocale()]}}
                                    </p>
                                @else
                                    <p class="text-nowrap">
                                        -
                                    </p>
                                @endif
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <label>@lang('orders.chang')</label>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <p class="text-nowrap">
                                    @if($order->change)
                                        {{$order->change->id}}
                                    @endif
                                </p>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <label>@lang('orders.worker')</label>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <p>{{$order->user->name}}</p>
                            </div>
                            @if($isAdmin)
                                <div class="col-xs-12">
                                    <div class="buttons">
                                        <a class="modalShow" href="#">@lang('orders.edit')</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-5">
                <div class="filter-block filter-order filter-order-two">
                    <h4>@lang('orders.pauses')</h4>
                    <div class="row">
                        @if($pauses)
                            @foreach($pauses as $key=>$pause)
                                <div class="col-xs-2"><span>{{$key+1}}</span></div>
                                <div class="col-xs-10">
                                    <p>@lang('orders.from') {{$pause['start']}} до {{$pause['end']}}</p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

        </div>
        <div class="user_table acts__table">
            <table>
                <tr class="td-one">
                    <td>ID</td>
                    <td>Товар</td>
                    <td>@lang('orders.price')</td>
                    <td>@lang('orders.amout')</td>
                    <td>@lang('orders.summa')</td>
                </tr>

                @if($order->table)
                        <tr>
                            <td>{{$order->table->id}}</td>
                            <td>{{$order->table->title}}</td>
                            <td></td>
                            <td></td>
                            <td>{{$order->amount}}</td>
                        </tr>
                @endif
            </table>
        </div>
        <div class="subtotal">
            <div class="subtotal-title">
                <p>@lang('orders.itog')</p>
            </div>
            <div class="subtotal-price">
                <p>{{$order->amount}} <img src="/img/many-orange.png" alt="many"></p>
            </div>
        </div>
    </div>
    @if($isAdmin)
        <a href="#x" class="overlay" id="win1"></a>
        <div class="win">
            <div class="form-user form-user-order">
                <div class="row">
                    <div class="col-xs-4 col-sm-6">
                        <p><span>ID : {{ $id }}</span></p>
                    </div>
                    <div class="col-xs-8 col-sm-6">
                        <p class="p">
                            <span class="h4">@lang('orders.editing')</span>
                        </p>
                    </div>
                </div>
                <read-order id="{{$id}}"></read-order>
            </div>
            <a class="close " id="closeWin" title="Закрыть " href="#close "></a>
        </div>
    @endif
@endsection

