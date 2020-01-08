@extends('layouts.app')
@php
    App::setLocale(session('lng'));
@endphp
@section('content')
    <section class="content-header">
        <h1>
            @lang('site.open_table')
        </h1>
    </section>
    <section class="content">  @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-xs-6">
                                <a href="{{route('orderstablecreate')}}" type="button"
                                   class="btn btn-success btn-block btn-default btn-lg"
                                   style="width: 100%; color: white;">+ @lang('site.add')
                                </a>
                            </div>

                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="table-container">
                                <div class="table-responsive">
                                    <table id="example1" class="table table-bordered table-striped tableOpenorder">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('site.stil')</th>
                                            <th>@lang('site.client')</th>
                                            <th>@lang('site.date_start')</th>
                                            <th>@lang('site.proshlo')</th>
                                            <th>
                                                @lang('site.priceThis')
                                            </th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($orders) > 0)
                                            @foreach($orders as $k=>$order)
                                                <tr>
                                                    <td>{{ $order->id }}</td>
                                                    <td>
                                                        @foreach ($order->tableReservation as $reservat)
                                                            @php
                                                                $tableTitle = App\Table::where('id', $reservat->id_table)->firstOrFail();
                                                            @endphp
                                                            {{$tableTitle->title }}
                                                        @endforeach

                                                    </td>
                                                    <td>
                                                        @if($order->customer_id == 0)
                                                            Гість
                                                        @else
                                                            @php
                                                                $customer = App\Customer::where('id', $order->customer_id)->firstOrFail();
                                                            @endphp
                                                            {{$customer->name  }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ Carbon\Carbon::parse($order->start )->format('d-m-Y H:i') }}
                                                    </td>
                                                    <td>
                                                            <timer-table
                                                                    @if($order->activepause)
                                                                        pause="1"
                                                                    @else
                                                                       pause="-1"
                                                                    @endif
                                                                    date="{{Carbon\Carbon::parse($order->start,$tz )->timestamp}}">
                                                            </timer-table>
                                                    </td>
                                                    <td>
                                                        <timer-priceorder start="{{$k}}"
                                                                          order_id="{{$order->id}}"></timer-priceorder>
                                                    </td>
                                                    <td class="tr_button">
                                                        <div class="buttonOpenTableConteer">
                                                            <a href="/pause?order={{$order->id}}&pause=@if( $order->activepause){{$order->tablePause->id}}@endif&table={{$order->table_id}}"
                                                               @if( $order->activepause )
                                                                     style="background-color: #d4232a; color: white"
                                                                     class="btn btn-app btn-default btn-pause "
                                                               @else
                                                                     class="btn btn-app btn-default "
                                                                    @endif >
                                                                @if( $order->activepause )
                                                                    <i class="fa fa-hourglass-1"></i> Возобновить
                                                                @else
                                                                    <i class="fa  fa-hourglass-3"></i> Пауза
                                                                @endif
                                                            </a>
                                                            <a  class="btn btn-primary  btn-app btn-success id3"
                                                               data-order="{{ $order->id }}" data-toggle="modal"
                                                               data-target="#modal-default">
                                                                <i class="fa fa-edit"></i> @lang('site.add') бар
                                                            </a>

                                                            <a href="/orderbil-closed/{{$order->id}}"
                                                               class="btn btn-app btn-warning ">
                                                                <i class="fa fa-unlock-alt"></i> Закрити
                                                            </a>
                                                        </div>

                                                    </td>

                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
    </section>

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">@lang('site.add')</h4>
                </div>
                <div class="modal-body">

                    <form method="POST" action="{{action('OrderController@storeSmall')}}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="text" id="orderid" name="orderid" value="0" class="form-control"
                               style="display:none">

                        <div class="row" id="raz">
                            <div class="col-xs-6 ">
                                <label>Товар</label>
                                <select class="form-control js-example-basic-single" name="product1" id="sel1">
                                    <option value="" selected>-</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->title }}
                                            (склад: {{ $product->count }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xs-3 ">
                                <label>Порция</label>
                                <p>
                                    <input type="number" value="1" min="0" max="1000" step="0.1" name="count1"
                                           class="form-control" id="sel2" autocomplete="no">
                                </p>
                            </div>
                        </div>
                        <div class="row" id="selectconnect">
                            <div class="col-xs-6" id="select_apend"></div>
                            <div class="col-xs-3" id="input_apend"></div>
                            <div class="col-xs-2 " id="del"
                                 style="margin-top: 1px; display: block; clear: inherit;   width: 100px;"></div>
                        </div>
                        <div class="row" style="position:relative; top: -15px;">
                            <div class="col-sm-9 col-lg-9">
                                <div class="addproduct btn btn-block btn-success" id="2" style="margin-top: 26px;">
                                    + @lang('site.add') продукт
                                </div>
                            </div>
                        </div>
                        <div class="" style="display: none">
                            <label>@lang('site.primitka')</label>
                            <textarea name="description"
                                      class="form-control">{{ $clients->description ?? '' }}</textarea>
                        </div>
                        <input id="b1" type='submit' value="Додати в замовлення" class="btn btn btn-primary active"/>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Закрити</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->
    <div class="modal modal-warning fade" id="modal-warning">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Warning Modal</h4>
                </div>
                <div class="modal-body">
                    <a id="id3" href="/close-bar?id={{$order->id ?? 0}}" class="btn btn-app btn-warning ">
                        <i class="fa fa-unlock-alt" data-toggle="modal" data-target="#modal-default"></i> Закрити
                    </a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-outline">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
