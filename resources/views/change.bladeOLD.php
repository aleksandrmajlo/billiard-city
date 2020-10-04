@extends('layouts.app')
@php
    App::setLocale(session('lng'));
@endphp
@section('content')
    <section class="content-header">
        <h1>
            @lang('site.change')
        </h1>
        <div class="row" style="margin-top: 10px;">
            <div class=" col-12  col-xs-12">
                <div class="box">
                    <form method="get" action="{{action('ChangeController@index')}}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row" style="padding: 10px;">
                            <div class=" col-12 col-md-12 col-xs-12">
                                <div class="col-12 col-md-2  col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input placeholder="от" type="date" name="ot" autocomplete="off"
                                               class="form-control datepicker-here" >
                                    </div>
                                </div>

                                <div class="col-12 col-md-2 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input placeholder="от" type="date" name="do" autocomplete="off"
                                               class="form-control datepicker-here" >
                                    </div>
                                </div>

                                @if(\Auth::user()->hasRole('admin'))
                                    <div class="col-12 col-md-2 col-xs-12">
                                        <div class="input-group">
                                            <select class="form-control" name="work">
                                                <option value="0">@lang('site.rabotnik')</option>
                                                @foreach($workers as $worker)
                                                    <option value="{{ $worker->id }}">{{  $worker->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-12 col-md-4  col-xs-12">
                                    <div class="input-group">
                                        <input type="submit" style="color: white"
                                               class="btn btn-success btn-block btn-default btn-lg"
                                               value="@lang('site.poisk')">
                                    </div>
                                </div>
                    </form>

                </div>
            </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                    </div>
                    <div class="box-body">
                        <div class="table-container">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>@lang('site.polzovatel')</th>
                                    <th>@lang('site.date_start')</th>
                                    <th>@lang('site.startzmini')</th>
                                    <th>@lang('site.clientiv')</th>
                                    <th>@lang('site.nal')</th>
                                    <th>@lang('site.cart')</th>
                                    <th>@lang('site.zarabotano')</th>
                                    <th>@lang('site.sumstartz')</th>
                                    <th>@lang('site.sumstendz')</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($changes as $change)
                                    @if($change)

                                        <tr>
                                            <td>{{ $change->id }}</td>
                                            <td>{{ $change->user->name ?? ''}}</td>
                                            <td>{{ $change->start }}</td>
                                            <td>   @if(empty($change->stop)) <span
                                                        class="green">зміна йде</span> @endif {{ $change->stop }}
                                            </td>
                                            <td>
                                                @if(isset($change->start) && isset($change->stop))
                                                    @php
                                                        $amountCountChange = \App\Order::where('created_at', '>=', $change->start)
                                                        ->where('created_at', '<=', $change->stop)
                                                         ->where('user_id', '=', $change->user_id)
                                                         ->sum('amount');

                                                      $amountCountClients = \App\Order::where('created_at', '>', $change->start)
                                                        ->where('created_at', '<', $change->stop)
                                                         ->where('user_id', '=', $change->user_id)
                                                         ->count();
                                                    @endphp
                                                @endif
                                                {{count($change->orders) }}
                                            </td>
                                            <td>
                                                    {{ $change->nal }} ₴
                                            </td>

                                            <td>
                                                    {{ $change->cart }} ₴
                                            </td>
                                            <td>
                                                    {{ $change->total }} ₴
                                            </td>
                                            <td> {{ $change->summa_start }} ₴</td>
                                            <td>
                                                @if(!empty($change->stop))
                                                    {{ $change->summa_end  }} ₴
                                                @endif
                                            </td>
                                            <td>
                                                <a href="/change/{{ $change->id }}">>>></a>
                                            </td>

                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $changes->appends($_GET)->links() }}

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
