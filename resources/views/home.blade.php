@extends('layouts.app')
@php
    App::setLocale(session('lng'));
@endphp
@section('content')

    {{-- старый код --}}
    <div class="row">
        @if(Auth::check())
            <div class="box-header">
                @if(\Auth::user()->hasRole('manager') || \Auth::user()->hasRole('barmen'))
                    @if($openChangeId == null)
                        <h2>@lang('act.open_smena')</h2>
                        <div class="text-center">
                            <a class="btn btn-primary btn-lg " href="{{url('open_order?user_id='.Auth::user()->id)}}">
                                @lang('act.open_smena')
                            </a>
                        </div>
                    @endif
                @endif
                @if(isset($openChangeSumStart))
                    @if(\Auth::user()->hasRole('manager') || \Auth::user()->hasRole('barmen'))
                        @if($openChangeId != null)
                            @if($openChangeId != null)
                                <div class="text-center">
                                    <a class="btn btn-primary btn-lg "
                                       href="{{url('close_order?id='.$openChangeId)}}"> @lang('act.close_smena')</a>
                                </div>
                            @endif
                        @endif
                    @endif
                @endif
            </div>
        @endif
    </div>
    {{-- старый код end --}}

    <div class="row">
        <div class="col-sm-3 col-sm-push-9 col-md-4 col-md-push-8">
            <div class="row">
                <div class="col-xs-xs-12 col-xs-6 col-sm-12">
                    <div class="info__block blue__bg">
                        <div class="info__block-title">
                            <p class="title"><img src="/img/bar.png" alt="bar">Bar</p>
                        </div>
                        <div class="info__block-info ">
                            <p>
                                <span>{{$change_stat['bar_count']}}</span>
                                <span class="info">@lang('orders.change_ordes')</span>
                            </p>
                            <p>
                                <span>{{$change_stat['bar_summa']}} ₴</span>
                                <span class="info">@lang('orders.summa_ordes')</span>
                            </p>
                            <p class="hidden-sm">
                                <span>{{$change_stat['bar_month']}} ₴</span>
                                <span class="info">@lang('orders.month_ordes')</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xs-xs-12 col-xs-6 col-sm-12">
                    <div class="info__block orange__bg">
                        <div class="info__block-title">
                            <p class="title"><img src="img/snooker.png" alt="Billiard">Billiard</p>
                        </div>
                        <div class="info__block-info">
                            <p><span>{{$change_stat['table_count']}}</span><span class="info">@lang('orders.change_ordes')</span>
                            </p>
                            <p><span>{{$change_stat['table_summa']}} ₴</span><span
                                        class="info">@lang('orders.summa_ordes')</span></p>
                            <p class="hidden-sm">
                                <span>{{$change_stat['table_month']}} ₴</span>
                                <span class="info">@lang('orders.month_ordes')</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xs-xs-12 col-xs-6 col-sm-12 hidden-sm">
                    <div class="info__block blue__bg ">
                        <div class="info__block-title">
                            <p class="title"><img src="img/client.png" alt="Client">Client</p>
                        </div>
                        <div class="info__block-info">
                            <p><span>{{$countOrders}}</span><span class="info">@lang('site.mann_smen')</span></p>
                            <p><span>100.000</span><span class="info">@lang('site.mann_month')</span></p>
                            <p><span>200.000</span><span class="info">@lang('site.mann_rek') январь 2019</span></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-sm-9 col-sm-pull-3 col-md-8 col-md-pull-4">

            <div class="table_block blue">
                <p>@lang('orders.open_order')</p>
                <div class="table">
                    <table class="tables">
                        @if($orders)
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <img src="/img/number.png" alt="number">{{$order->id}}
                                    </td>
                                    <td>
                                        <img src="/img/many.png" alt="number">{{$order->barprice}}
                                    </td>
                                    <td>
                                        @if(!empty($order->info))
                                            <a href="/order-closed/{{$order->id}}">
                                                <div class="non">
                                                    <img src="/img/coment.png" alt="number">{{$order->info}}
                                                </div>
                                                <div class="none">
                                                    <img src="/img/coment.png" alt="number">{{$order->info}}
                                                </div>
                                            </a>
                                        @else

                                        @endif

                                    </td>
                                    <td>
                                        <a href="/order-closed/{{$order->id}}">
                                            <img class="mini" src="/img/plus.png" alt="">
                                            <div class="non">@lang('orders.more') +</div>
                                            <div class="nono">
                                                <img src="/img/plus.png" alt="">подроб...
                                            </div>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                    </table>
                </div>
                <a class="btn-plus" href="{{url('/orders-create')}}"><img src="/img/btn-plus.png" alt="btn"></a>
            </div>

            <div class="table_block orange">
                <p>@lang('site.open_table')</p>
                <div class="table">
                    <table class="tables">
                        @if($open_tables)
                            @foreach($open_tables as $k=>$open_table)
                                <tr>
                                    <td><img src="/img/number.png" alt="number">{{$open_table->table->number}}</td>
                                    <td class="">
                                        <div class="timerTableHome">
                                            <img src="/img/clock.png" alt="number">
                                            <timer-table
                                                    @if($open_table->activepause)
                                                    pause="1"
                                                    @else
                                                    pause="-1"
                                                    @endif
                                                    date="{{Carbon\Carbon::parse($open_table->start )->timestamp}}">
                                            </timer-table>
                                            <span class="openText">@lang('table.open')</span>
                                        </div>
                                    </td>
                                    <td class="price">
                                        <img src="/img/many.png" alt="number">
                                        <timer-priceorder start="{{$k}}"
                                                          order_id="{{$open_table->id}}"></timer-priceorder>
                                    </td>
                                    <td><img src="/img/stol.png" alt="number">
                                        №{{$open_table->table->number}}
                                        <strong class="bill">
                                            {{$open_table->table->name}}
                                        </strong>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                    </table>
                </div>
                <a class="btn-plus" href="/orders-table-create"><img src="/img/btn-plus.png" alt="btn"></a>
            </div>


            <div class="table_block gray">
                <p>@lang('site.next_book')</p>
                <div class="table">
                    <table class="tables">
                        @if($reservs)
                            @foreach($reservs as $reserv)
                                <tr>
                                    <td>
                                        <img src="/img/number.png" alt="number">{{$reserv->id}}
                                    </td>
                                    <td>
                                        @php
                                            $table=\App\Table::where('id', '=', $reserv->id_table)->first();
                                        @endphp
                                        <div class="non"><img src="/img/stol.png"
                                                              alt="number">@lang('site.tableSmall') {{$table->numer}}
                                        </div>
                                        <div class="none"><img src="/img/stol.png" alt="number">{{$table->numer}}</div>
                                    </td>
                                    <td><img src="/img/user.png" alt="number"></td>
                                    <td><img src="/img/colendar-s.png"
                                             alt="number">{{ Carbon\Carbon::parse($reserv->booking_from )->format('d-m-Y') }}
                                    </td>
                                    <td><img src="/img/clock.png"
                                             alt="number">{{ Carbon\Carbon::parse($reserv->booking_from )->format('H:i') }}
                                        - {{ Carbon\Carbon::parse($reserv->booking_before )->format('H:i') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
                <a class="btn-plus" href="/reserv-table-create">
                    <img src="/img/btn-plus.png" alt="btn">
                </a>
            </div>
        </div>
    </div>
@endsection
