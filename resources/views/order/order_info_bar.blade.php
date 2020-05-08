@extends('layouts.app')
@section('content')
    <div class="user">

        <div class="user__title">
            <h2>@lang('site.zakaz')</h2>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-7">
                <div class="filter-block filter-order">
                    <form class="filter-form" action="" method="post">
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
                                    @if($order->customer)
                                        {{$order->customer->skidka_bar}}%
                                    @else
                                        -
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
                @if($order->bars)
                    @foreach ($order->bars as $bar)
                        <tr>
                            <td>{{$bar->stock->id}}</td>
                            <td>{{$bar->stock->title}}</td>
                            <td>{{$bar->stock->price}}</td>
                            <td>{{$bar->count}}</td>
                            <td>{{$bar->price}}</td>
                        </tr>
                    @endforeach
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
