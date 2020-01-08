@extends('layouts.app')
@php
    App::setLocale(session('lng'));
@endphp
@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <section class="content">

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <h1>Змiна #{{ $change->id }}</h1>
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Користувач</th>
                                    <th>Старт зміни</th>
                                    <th>Закінчення зміни</th>
                                    <th>Клієнтів</th>
                                    <th>Зароблено</th>
                                    <th>Сума на початок</th>
                                    <th>Сума на закриття</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{ $change->user->name }}</td>
                                    <td>{{ $change->start }}</td>
                                    <td>
                                        @if(empty($change->stop)) <span class="green">зміна йде</span> @endif {{ $change->stop }}</td>
                                    <td>
                                        {{ count($change->orders) }}
                                    </td>
                                    <td>
                                        {{ $change->total  }}  ₴
                                    </td>
                                    <td>
                                        {{ $change->summa_start }} ₴
                                    </td>
                                    <td>
                                        {{ $change->summa_end  }} ₴
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="table-container">
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Тип</th>
                                        <th>Стiл</th>
                                        <th>@lang('site.date_start')</th>
                                        <th>@lang('site.date_end')</th>
                                        <th>@lang('site.summa')</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $order->id }} </td>
                                            <td>@if($order->type_billiards == 1) більярдна @endif @if($order->type_bar == 1)
                                                    бар @endif</td>
                                            <td>{{$order->tableId->title ?? '-'}}</td>
                                            <td>{{ $order->start }}</td>
                                            <td>{{ $order->closed ?? "не закрите" }}</td>

                                            <td>
                                                @if($order->amount == 0) не закрите
                                                @else
                                                    @if($order->type_billiards == 1)
                                                             {{$order->amount}} грн.
                                                    @endif
                                                    @if($order->type_bar == 1)
                                                            {{$order->barprice}} грн.
                                                    @endif

                                                @endif
                                            </td>
                                            <td><a href="/info/{{ $order->id }}">>>></a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection