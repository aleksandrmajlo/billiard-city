@extends('layouts.app')
@php
    App::setLocale(session('lng'));
@endphp
@section('content')

    <div class="user statPageUser">
        <div class="user__title">
            <h2> @lang('site.stat')</h2>
            <div class="filter-block filter-order">
                <h4>@lang('orders.filter')</h4>
                <form method="get" action="{{action('StatisticsController@index')}}" class="filter-form">
                    <div class="row">
                        @if($isAdmin)
                            <div class="col-md-7">
                                <div class="overflowHidden">
                                    <div class="col-xs-4 col-xs-xs-xs-12 left-pad">
                                        <label>@lang('site.rabotnik')</label>
                                    </div>
                                    <div class="col-xs-8 col-xs-xs-xs-12">
                                        <select name="user_id">
                                            <option value="0">@lang('orders.all')</option>
                                            @foreach($workers as $worker)
                                                <option @if($req_user==$worker->id) selected
                                                        @endif value="{{ $worker->id }}">{{  $worker->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="overflowHidden">
                                    <div class="col-xs-4 col-xs-xs-xs-12 left-pad"><label>Тип</label></div>
                                    <div class="col-xs-8 col-xs-xs-xs-12">
                                        <select name="type">
                                            <option value="0">@lang('orders.all')</option>
                                            <option value="1"
                                                    @if(isset($_GET['type']) && $_GET['type'] == 1) selected @endif>Бар
                                            </option>
                                            <option value="2"
                                                    @if(isset($_GET['type']) && $_GET['type'] == 2) selected @endif>
                                                Бильярдна
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-5">

                            <div class="col-xs-4 col-xs-xs-xs-12 left-pad">
                                <label>@lang('orders.date_from_to')</label>
                            </div>
                            <div class="col-xs-8 col-xs-xs-xs-12 right-pad">
                                <input class="order-time" value="{{request()->get('date_start')}}" name="date_start"
                                       type="date" placeholder="20.12.2019">
                                <input class="order-time" value="{{request()->get('date_end')}}" name="date_end"
                                       type="date" placeholder="20.01.2020">
                            </div>
                            <div class="col-xs-4 col-xs-xs-xs-12 left-pad">
                                <label>@lang('orders.time_from_to')</label>
                            </div>

                            <div class="col-xs-8 col-xs-xs-xs-12 right-pad">
                                <input class="order-time" value="{{request()->get('time_start')}}" name="time_start"
                                       type="time" placeholder="23:50">
                                <input class="order-time" value="{{request()->get('time_end')}}" name="time_end"
                                       type="time" placeholder="09:50">
                            </div>
                            <div class="col-xs-12">
                                <div class="buttons">
                                    <a href="{{route('history_orders')}}" type="reset">@lang('act.reset')</a>
                                    <button type="submit">@lang('act.send')</button>
                                </div>
                            </div>

                        </div>


                    </div>
                </form>
            </div>
        </div>
        <div class="blue liken invoic hidden">
            <form method="get" name="searchform" id="searchform" action="">
                <input name="" type="text" placeholder=''>
                <button type="submit"><img src="img/search.png" alt="search"></button>
            </form>
        </div>
        <div class="user_table acts__table">
            <table>

                <tr class="td-one">
                    <td>id</td>
                    <td>Тип</td>
                    <td>@lang('site.date_start')</td>
                    <td>@lang('site.date_end')</td>
                    <td>@lang('site.rabotnik')</td>
                    <td>@lang('site.summa')</td>
                    <td></td>
                </tr>
                @foreach($orders as $order)
                    <tr>
                        <td>{{$order->id}}</td>
                        <td>{{$order->type}}</td>
                        <td>{{ $order->start }}</td>
                        @if(is_null($order->closed))
                            <td>@lang('orders.not_close')</td>
                        @else
                            <td>{{$order->closed}}</td>
                        @endif
                        <td>
                            @if($order->user)
                                {{$order->user->name}}
                            @endif

                        </td>
                        <td> {{ $order->amount }}</td>
                        <td>
                            <a href="/info/{{$order->id}}"><img src="/images/page-next.svg"></a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        @include('pagination.default', ['paginator' => $orders])
        {{--        {{ $orders->appends($_GET)->links() }}--}}
        <div>
            <div style="text-align: right">
                @if(isset($ordersSum))
                    <h3> {{ $ordersSum   }}</h3>
                @endif
            </div>
        </div>

    </div>

@endsection
