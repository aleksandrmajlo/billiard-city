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
                    <div class="box-body orderClosedConteer">
                        <h1>@lang('site.zakaz') #{{ $order->id }}</h1>
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Товар</th>
                                    <th>@lang('site.start')</th>
                                    <th>@lang('site.end')</th>
                                    <th>Цена</th>
                                    <th>Шт./Хв.</th>
                                    <th>@lang('site.summa')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!$order->bars->isEmpty())
                                    @foreach($order->bars as $product)
                                        <tr>
                                            <td>
                                                {{$product->stock->title}}
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                {{$product->stock->price}} {{$money}}
                                            </td>
                                            <td> {{ $product->count }} </td>
                                            <td>
                                                {{round($product->count * $product->stock->price, 2)  }} {{$money}}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if($pauses)
                                    @foreach($pauses as $pause)
                                        <tr>
                                            <td>{{$order->table->title}} - пауза</td>
                                            <td>{{ Carbon\Carbon::parse($pause->start_pause)->format('d-m-Y H:i') }}</td>
                                            @if($pause->end_pause)
                                                <td>{{ Carbon\Carbon::parse($pause->end_pause)->format('d-m-Y H:i') }}</td>
                                            @else
                                                <td>{{ Carbon\Carbon::now()->format('d-m-Y H:i') }}</td>
                                            @endif
                                            <td>0 {{$money}}</td>
                                            <td>
                                                @php
                                                    if($pause->end_pause){
                                                         $pauseMinutes=Carbon\Carbon::parse($pause->start_pause)->diffInMinutes(Carbon\Carbon::parse($pause->end_pause), false);
                                                    }else{
                                                         $pauseMinutes=Carbon\Carbon::parse($pause->start_pause)->diffInMinutes(Carbon\Carbon::now(), false);
                                                    }
                                                    echo $pauseMinutes;
                                                @endphp
                                            </td>
                                            <td>0 {{$money}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <td>{{$order->table->title}}</td>
                                    <td>{{ $s1->format('d-m-Y H:i') }}</td>
                                    <td>{{ $s2->format('d-m-Y H:i') }}</td>
                                    <td class="text-center">
                                        {{$price['priceOrderMinutes']}}  {{$money}}
                                        <br>(це є середня ціна за хвилину для стола)
                                    </td>
                                    <td>{{$minutes}}</td>
                                    <td>{{$price['priceOrder']}}  {{$money}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <dl class="row">
                            <dt class="col-sm-3 col-lg-1">@lang('site.start'):</dt>
                            <dd class="col-sm-9 col-lg-11">{{ Carbon\Carbon::parse($s1)->format('d-m-Y H:i') }}</dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-3 col-lg-1">@lang('site.end'):</dt>
                            <dd class="col-sm-9 col-lg-11">{{ Carbon\Carbon::parse($s2)->format('d-m-Y H:i') }}</dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-3 col-lg-1">@lang('site.vivid'):</dt>
                            <dd class="col-sm-9 col-lg-11">
                                @if(isset($customer))
                                    {{$customer->name  }}  {{$customer->surname  }}
                                @else
                                    @lang('site.gost')
                                @endif
                            </dd>
                        </dl>
                        <hr>
                        <h2>Звіт по часу</h2>
                        <dl class="row">
                            <dt class="col-sm-3 col-lg-3">загальний час:</dt>
                            <dd class="col-sm-9 col-lg-9">{{ $Total_minutes }}</dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-3 col-lg-3">час гри:</dt>
                            <dd class="col-sm-9 col-lg-9">{{ $minutes }}</dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-3 col-lg-3">час пауз:</dt>
                            <dd class="col-sm-9 col-lg-9">{{ $pauseMinutes }}</dd>
                        </dl>
                        <h2>Звіт по столу</h2>
                        <dl class="row">
                            <dt class="col-sm-3 col-lg-3">сума:</dt>
                            <dd class="col-sm-9 col-lg-9">{{ $price['priceOrder'] }}</dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-3 col-lg-3">сума від якої знижка(не враховуються перші 60 хв.):</dt>
                            <dd class="col-sm-9 col-lg-9">{{ $price['priceOrderWithout'] }}</dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-3 col-lg-3">сума знижки:</dt>
                            <dd class="col-sm-9 col-lg-9">{{ $price['priceOrderDiscount'] }}</dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-3 col-lg-3">процент знижки(%):</dt>
                            <dd class="col-sm-9 col-lg-9">{{ $price['priceProcentOrder'] }}</dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-3 col-lg-3">сума за стіл зі знижкою:</dt>
                            <dd class="col-sm-9 col-lg-9">{{ $price['priceOrderTotal'] }}</dd>
                        </dl>

                        <hr>

                        @if(!$order->bars->isEmpty())
                            <h2>Звіт по продуктам</h2>
                            <dl class="row">
                                <dt class="col-sm-3 col-lg-3">сума за продукти:</dt>
                                <dd class="col-sm-9 col-lg-9">{{ $price['priceProduct'] }}</dd>
                            </dl>

                            <dl class="row">
                                <dt class="col-sm-3 col-lg-3">сума знижки:</dt>
                                <dd class="col-sm-9 col-lg-9">{{ $price['priceProductsDiscount'] }}</dd>
                            </dl>
                            <dl class="row">
                                <dt class="col-sm-3 col-lg-3">процент знижки(%):</dt>
                                <dd class="col-sm-9 col-lg-9">{{ $price['priceProcentProducts'] }}</dd>
                            </dl>

                            <dl class="row">
                                <dt class="col-sm-3 col-lg-3">сума до сплати:</dt>
                                <dd class="col-sm-9 col-lg-9">{{ $price['priceProductsTotal'] }}</dd>
                            </dl>
                            <hr>
                        @endif


                        <h2>Сума за замовлення:</h2>
                        <p class="text-primary text-capitalize text-bold"
                           style="font-size: 140%;">{{ $price['priceTotal'] }} </p>

                        <form method="POST" action="{{action('OrderController@orderBillClosedOrder')}}">
                            {{ csrf_field() }}
                            <div class=" form-group">
                                <label>@lang('site.type_playment')</label>
                                <select class="form-control" name="billing" id="sel1">
                                    <option value="1">@lang('site.price_nal')</option>
                                    <option value="2">@lang('site.price_kart')</option>
                                </select>
                            </div>

                            <div class=" form-group">
                                <label>@lang('site.primitka')</label>
                                <textarea name="info" class="form-control"></textarea>
                            </div>
                            <input type="hidden" name="sum_booking" value="{{ $price['priceTotal'] }}">
                            <input type="hidden" name="min" value="{{ $minutes }}">
                            <input type="hidden" name="priceAmount" value="{{ $price['priceTotal'] }}">
                            <input type='hidden' value="{{ $order->reservation_id }}" name="idreserv"/>
                            <input id="b1" type='hidden' value="{{ $order->id }}" name="id"/>
                            <input id="b1" type='submit' value="@lang('site.close')"
                                   class="btn btn btn-lg btn-warning active "/>
                        </form>

                    </div>
                </div>
            </div>

            <div class="col-xs-12">
                <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button"
                   aria-expanded="false" aria-controls="collapseExample">
                    Price Log Minutes
                </a>
                <div class="collapse" id="collapseExample">
                    <div class="card card-body">

                        @if($LogData)
                            @foreach($LogData['price'] as $key=>$price)
                                @if($price)
                                    <dl class="dl-horizontal">
                                        <dt>
                                            {{$key+1}}-
                                            @if($LogData['minutes'][$key])
                                                <b>{{$LogData['minutes'][$key]}}</b>
                                            @endif
                                        </dt>
                                        <dd>{{$price}} грн.</dd>
                                    </dl>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>


        </div>
    </section>
@endsection