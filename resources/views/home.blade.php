@extends('layouts.app')
@php
    App::setLocale(session('lng'));
@endphp
@section('content')
    <section class="content-header">
        <h1>
            @lang('site.panel')
        </h1>
    </section>
    <section class="content"> @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        @if(isset($openChangeSumStart))
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-opencart"></i></span>
                        <div class="info-box-content">
                            @if(\Auth::user()->hasRole('manager'))
                                <span class="info-box-number">  <i class="fa fa-life-bouy"></i> {{$sumBar}} ₴</span>
                            @endif
                            @if(\Auth::user()->hasRole('barmen'))
                                <span class="info-box-number">  <i class="fa fa-beer"></i> {{$sumBar}} ₴</span>
                            @endif
                            @if(\Auth::user()->hasRole('admin'))
                                <span class="info-box-number">  <i class="fa fa-life-bouy"></i> {{$sumBillAdmin  }}
                                    ₴</span>
                                <span class="info-box-number">  <i class="fa fa-beer"></i> {{$sumBarAdmin  }} ₴</span>
                                @if(\Auth::user()->hasRole('admin') )
                                    <a data-toggle="modal" data-target="#modal-default">Зняти гроші</a> (<a
                                            href="/info-money">інфо</a>)
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"> @lang('site.clients_day')</span>
                            <span class="info-box-number">{{ $countOrders }}</span>
                            <span class="info-box-number">Нових: {{ $customerCountNew }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow">
                            <i class="fa fa-money"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">@lang('site.sum_day')</span>
                            <span class="info-box-number">
                                <i class="fa fa-life-bouy"></i>
                                {{ $sumBillAdmin2 }} ₴
                            </span>
                            <span class="info-box-number">
                                <i class="fa fa-beer"></i> {{ $sumBarAdmin2}} ₴
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <section class="col-lg-7 connectedSortable ui-sortable">
                <div class="box">
                    <div class="box-header">
                        <h4>@lang('site.next_book')</h4>
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Бронь</th>
                                <th>до</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($booking as $book)
                                <tr>
                                    <td><a href="/booking/{{ $book->id }}">{{ $book->id }}</a></td>
                                    <td>{{ $book->booking_from }}</td>
                                    <td>{{ $book->booking_before }}</td>
                                    <td>{{ $book->a_guest }}
                                        @if($book->id_customers)
                                            @php
                                                $customer = App\Customer::where('id', $book->id_customers)->firstOrFail();
                                            @endphp
                                            {{$customer->name ?? " "}} ({{$customer->phone ?? " "}})
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <a href="/reserv-table-create" type="button" class="btn btn-block btn-lg btn-success"
                           style="width: 200px;">+ @lang('site.add') бронь</a>
                    </div>
                </div>
                <div class="box">
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
                                @if(Auth::check())
                                    @if(\Auth::user()->hasRole('manager') || \Auth::user()->hasRole('barmen'))
                                        @if($openChangeId != null)
                                            <div class="text-center">
                                                <a class="btn btn-primary btn-lg " href="{{url('close_order?id='.$openChangeId)}}"> @lang('act.close_smena')</a>
                                            </div>
                                        @endif
                        </div>
                        <div class="box-body">
                            <ul class="todo-list ui-sortable">
                                @foreach($changes as $change)
                                    <li>
                                        <img src="https://crm.billiard-city.com//{{ $change->user->avatar ?? ' ' }}"
                                             alt="User Image" class="user-image"/> {{ $change->user->name ?? ' ' }}
                                        <span class="text">#{{ $change->id }}, (початок: {{ $change->start }}, кінець: {{ $change->stop ?? "йде"}})</span>
                                    </li>
                                @endforeach
                            </ul>
                            @endif
                            @endif
                            @endif
                        </div>

                    @endif
                    @if(\Auth::user()->hasRole('admin'))
                        <div class="box-header">
                            <h4>@lang('site.change')</h4>
                            <ul class="todo-list ui-sortable">
                                @if(count($changes) > 0)
                                    @foreach($changes as $change)
                                        <li>
                                            <img src="https://crm.billiard-city.com//{{ $change->user->avatar ?? '' }}"
                                                 alt="User Image" class="user-image"/> {{ $change->user->name ?? '' }}
                                            <span class="text">#{{ $change->id ?? '' }}, (@lang('site.open_change')
                                                : {{ $change->start ?? '' }}, @lang('site.close_change')
                                                : {{ $change->stop ?? 'актуально'}})</span>
                                        </li>
                                    @endforeach
                            </ul>
                        </div>
                    @endif
                    @endif
                </div>
            </section>
            <section class="col-lg-5 connectedSortable ui-sortable">
                <div class="box">
                    <div class="box-header">
                        <h4>@lang('site.table_book') </h4>
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>@lang('site.nachalo')</th>
                                <th>@lang('site.stil')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($ordersTables) > 0)
                                @foreach($ordersTables as $ordersTable)
                                    <tr>
                                        <td>{{  \Carbon\Carbon::parse($ordersTable->start)->format('d-m-Y H:i')}}</td>
                                        <td>
                                            @foreach ($ordersTable->tableReservation as $reservat)
                                                @php
                                                    $tableTitle = App\Table::where('id', $reservat->id_table)->firstOrFail();
                                                @endphp
                                                {{$tableTitle->title }}
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="box">
                    <div class="box-header">
                        <h4>@lang('site.clients_new')</h4>
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            </thead>
                            <tbody>
                            @foreach($customerNew as $customer)
                                <tr>
                                    <td>{{ $customer->name }} {{ $customer->surname }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <a href="{{route('customerscreate')}}" type="button" class="btn btn-block  btn-lg btn-success"
                           style="width: 200px;">+ @lang('site.add')
                        </a>
                    </div>
                </div>
            </section>
        </div>
        <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{action('MoneyController@edit')}}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input name="type" type="radio" value="1"> <label>Більярдна </label>
                            <br><input name="type" type="radio" value="2"> <label>Бар </label>
                            <br><label>Грошей</label>
                            <input type="number" name="admin" class="form-control" value="0" required/>
                            <br> <input type='submit' class="btn btn-primary active"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
