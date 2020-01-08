@extends('layouts.app')
@php
    App::setLocale(session('lng'));
@endphp
@section('content')
    <section class="content-header">
        <h1>
            @lang('site.stat')
        </h1>
        <div class="row" style="margin-top: 10px;">
            <div class=" col-12  col-xs-12">
                <div class="box">
                    <form method="get" action="{{action('StatisticsController@index')}}">

                        <div class="row" style="padding: 10px;">

                            <div class=" col-12  col-xs-12">
                                <div class="col-3 col-xs-12 col-md-1 ">
                                    <select class="form-control" name="type">
                                        <option value="0"
                                                @if(isset($_GET['type']) && $_GET['type'] == 0) selected @endif>Тип
                                        </option>
                                        <option value="1"
                                                @if(isset($_GET['type']) && $_GET['type'] == 1) selected @endif>Бар
                                        </option>
                                        <option value="2"
                                                @if(isset($_GET['type']) && $_GET['type'] == 2) selected @endif>
                                            Бильярдна
                                        </option>
                                    </select>
                                </div>

                                <div class="col-12 col-xs-12 col-md-2 ">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input placeholder="от" type="text" name="ot" autocomplete="off"
                                               class="form-control datepicker-here" data-date-format="yyyy-mm-dd"
                                               data-time-format="hh:ii" data-timepicker="true"
                                               data-position="bottom left" value="{{ $_GET['ot'] ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-12 col-xs-12 col-md-2 ">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input placeholder="от" type="text" name="do" autocomplete="off"
                                               class="form-control datepicker-here" data-date-format="yyyy-mm-dd"
                                               data-time-format="hh:ii" data-timepicker="true"
                                               data-position="bottom left" value="{{ $_GET['do'] ?? '' }}">
                                    </div>
                                </div>


                                <div class="col-12 col-xs-12 col-md-2 ">
                                    <div class="input-group">
                                        <select class="form-control" name="work">
                                            <option value="0">@lang('site.rabotnik')</option>
                                            @foreach($workers as $worker)
                                                <option value="{{ $worker->id }}">{{  $worker->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-12 col-xs-12 col-md-2 ">
                                    <div class="input-group">
                                        <select class="form-control js-example-basic-single" id="sel1"
                                                style="width: 150px;" name="customer">
                                            <option value="0">@lang('site.client')</option>
                                            @foreach($customers as $customer)
                                                <option class="form-control"
                                                        value="{{ $customer->id }}">{{ $customer->phone }}
                                                    ({{ $customer->name }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-3 col-xs-12 col-md-2 ">
                                    <select class="form-control" name="stil">
                                        <option value="0">Стiл</option>
                                        @foreach($tables as $table)
                                            <option class="form-control"
                                                    value="{{ $table->id }}">{{ $table->title }} </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-12 col-xs-12 col-md-1 ">
                                    <div class="input-group">
                                        <input type="submit" style="color: white"
                                               class="btn btn-success btn-block btn-default btn-lg" value=">">
                                    </div>
                                </div>
                                <label><input type="checkbox" value="1" name="billgreen"> показати тільки нічні столи


                                </label>
                    </form>

                </div>
            </div>

    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
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
                                        <th>@lang('site.rabotnik')</th>
                                        <th>@lang('site.summa')</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>
                                                {{ $order->id }}
                                            </td>
                                            <td>
                                                @if($order->type_billiards == 1) більярдна @endif @if($order->type_bar == 1)
                                                    бар @endif
                                            </td>
                                            <td>{{$order->tableId->title ?? '-'}}</td>
                                            <td>{{ $order->start }}</td>
                                            <td>{{ $order->closed ?? "не закриті" }}</td>
                                            <td>
                                                @if(isset($order->user_id))
                                                    @php
                                                        $user = App\User::where('id', '=', $order->user_id)->first();
                                                    @endphp
                                                    {{$user->name ?? 'нема' }}
                                                @endif
                                            </td>
                                            <td>
                                                @if(is_null($order->closed))
                                                    не закриті
                                                @else
                                                    {{ $order->barprice }} грн.
                                                @endif
                                            </td>
                                            <td>
                                                <a href="/info/{{ $order->id }}">>>></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ $orders->appends($_GET)->links() }}
                        <div style="text-align: right">@if(isset($ordersSum))<h2> {{ $ordersSum   }}   </h2>@endif</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
