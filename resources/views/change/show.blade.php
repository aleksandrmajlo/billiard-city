@extends('layouts.app')
@section('content')
    <div class="user">
        <div class="user__title">
            <h2>@lang('site.changeName') #{{ $change->id }}</h2>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-7">
                <div class="filter-block filter-order">
                    <form class="filter-form">
                        <div class="row">
                            <div class="col-xs-3 col-xs-xs-6">
                                <label>@lang('site.changeUser')</label>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <p>{{ $change->user->name }}</p>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <label>@lang('site.changeStart')</label>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <p>{{ $change->start }}</p>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <label>@lang('site.changeEnd')</label>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <p>
                                    @if(empty($change->stop))
                                        <span class="green">@lang('site.changeGo')</span>
                                    @endif {{ $change->stop }}
                                </p>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <label>@lang('site.clientiv')</label>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <p>
                                    {{ count($change->orders) }}
                                </p>
                            </div>

                            <div class="col-xs-3 col-xs-xs-6">
                                <label>@lang('site.nal')</label>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <p>
                                    {{ $change->nal  }} ₴
                                </p>
                            </div>

                            <div class="col-xs-3 col-xs-xs-6">
                                <label>@lang('site.cart')</label>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <p>
                                    {{ $change->cart  }} ₴
                                </p>
                            </div>

                            <div class="col-xs-3 col-xs-xs-6">
                                <label>@lang('site.zarabotano')</label>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <p>
                                    {{ $change->total  }} ₴
                                </p>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <label>@lang('site.sumstartz')</label>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <p>
                                    {{ $change->summa_start }} ₴
                                </p>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <label>@lang('site.sumstendz')</label>
                            </div>
                            <div class="col-xs-3 col-xs-xs-6">
                                <p>
                                    {{ $change->summa_end??0  }} ₴
                                </p>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>
        <div class="user_table acts__table">
            <table>
                <tr class="td-one">
                    <td>#</td>
                    <td>Тип</td>
                    @if($change->user->hasRole('barmen'))
                        <td style="max-width: 20%;">@lang('site.Comments')</td>
                    @else
                        <td>@lang('site.stil')</td>
                    @endif
                    <td>@lang('site.date_start')</td>
                    <td>@lang('site.date_end')</td>
                    <td>@lang('site.summa')</td>
                    <td></td>
                </tr>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }} </td>
                        <td>@if($order->type_billiards == 1) більярдна @endif
                            @if($order->type_bar == 1) бар @endif
                        </td>
                        @if($order->type_billiards == 1)
                            <td> {{$order->tableId->title ?? '-'}}</td>
                        @else
                            <td>{{$order->info}}</td>
                        @endif
                        <td>{{ $order->start }}</td>
                        <td>
                            @if(is_null($order->closed))
                                @lang('site.OrdersNotClose')
                            @else
                                {{$order->closed}}
                            @endif
                        </td>
                        <td>
{{--                            @if(is_null($order->closed))--}}
{{--                                @lang('site.OrdersNotClose')--}}
{{--                            @else--}}
                                @if($order->type_billiards == 1)
                                    {{$order->amount}} грн.
                                @endif
                                @if($order->type_bar == 1)
                                    {{$order->amount}} грн.
                                @endif
{{--                            @endif--}}
                        </td>
                        <td>
                            <a href="/info/{{ $order->id }}">>>></a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        @include('pagination.default', ['paginator' => $orders])
    </div>
@endsection
