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
                    <div class="box-body"><h1>@lang('site.zakaz') #{{ $orderIds }}</h1>
                        <table id="example1" class="table table-bordered table-striped" style="width: 60%;">
                            <thead>
                            <tr>
                                <th>Товар</th>
                                <th>Цена</th>
                                <th>Шт./Мiн.</th>
                                <th>@lang('site.summa')</th>
                            </tr>
                            </thead>
                            <tbody>@if(count($products) > 0)
                                @foreach($products as $product)
                                    <tr>
                                        <td>
                                            @php
                                                $prduct = App\Stock::where('id', $product->product_id)->firstOrFail();
                                            @endphp
                                            {{$prduct->title }}
                                        </td>
                                        <td> {{ $prduct->price }} грн.</td>
                                        <td> {{ $product->count }} шт.</td>
                                        <td>{{ $sump = round($product->count * $prduct->price, 2)  }} ₴
                                    </tr>
                                    @php
                                        $pr[] = $sump;
                                    @endphp
                                @endforeach
                            @endif
                            <tr>
                                <td>
                                    @php
                                        $table = App\Table::where('id', $table)->firstOrFail();
                                    @endphp
                                        {{$table->title }}
                                </td>
                                <td>
                                    {{ round($summin, 2) }} ₴/мiн.
                                </td>
                                <td>
                                    @if($priceOrder <= $tableZ->max_min && $min > $tableZ->min_min)
                                        {{ $min }} (мiнiмум: {{$priceOrder}} грн.)
                                    @else
                                        {{ $min }}
                                    @endif
                                    мiн.
                                </td>
                                <td>
                                    @if($min < $tableZ->min_min)-
                                    @else
                                        @if($priceOrder <= $tableZ->max_min)
                                            {{ $priceOrder }} ₴
                                        @else
                                            {{ round($count, 2) }} ₴
                                        @endif
                                    @endif

                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <br>
                        <hr>
                        <h3>
                            <dl class="dl-horizontal">
                                <dt>Початок:</dt>
                                <dd>{{ Carbon\Carbon::parse($s1)->format('d-m-Y H:i') }}</dd>
                            </dl>
                            <dl class="dl-horizontal">
                                <dt>Кінець:</dt>
                                <dd>{{ Carbon\Carbon::parse($s2)->format('d-m-Y H:i') }}</dd>
                            </dl>
                        </h3>
                        @if(isset($pauseId) && count($pauseId) > 0)
                            <b>паузи</b>
                            <table style="border: 1px solid silver; padding: 5px;">
                                <tr>
                                    <td style="border: 1px solid silver; padding: 5px;">Початок
                                    </td>
                                    <td style="border: 1px solid silver; padding: 5px;">Кiнець
                                    </td>
                                </tr>
                                @foreach($pauseId as $paus)
                                    <tr>
                                        <td style="border: 1px solid silver; padding: 5px;">
                                            {{ Carbon\Carbon::parse($paus->start_pause)->format('d-m-Y H:i') }}
                                        </td>
                                        <td style="border: 1px solid silver; padding: 5px;">
                                            {{ Carbon\Carbon::parse($paus->end_pause)->format('d-m-Y H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                        @endif
                        <h3>
                            <hr>
                            @if(isset($customer))
                                {{$customer->name  }}  {{$customer->surname  }}
                                <br><br>
                                Сума: {{ $priceOrder }}₴
                                <h4 style="color: green">Знижка: {{$customer->skidka ?? '0' }}%</h4>
                                @lang('site.itogo'):
                                {{--Итого--}}
                                <h2> 
                                    @if($priceOrderDiscount || $priceOrderDiscount == 0){{$priceOrderDiscount}}
                                    @else {{$priceOrder}} @endif
                                    ₴
                                </h2>
                                <h3>
                            @else
                                        Гiсть
                                        <h4 style="color: green">Знижка: 0%</h4>
                                        @lang('site.itogo'):
                                        {{--Итого--}}
                                        <h2> @if($priceOrderDiscount)
                                                {{$priceOrderDiscount}}
                                             @else
                                                {{$priceOrder}}
                                             @endif
                                            ₴
                                        </h2>
                                    @endif
                                </h3>
                                <hr>
                                <form method="POST" action="{{action('OrderController@orderBillClosedOrder')}}"  enctype="multipart/form-data"/>
                                {{ csrf_field() }}
                                <label>@lang('site.type_playment')</label>
                                <select class="form-control" name="billing" id="sel1">
                                    <option value="1">@lang('site.price_nal')</option>
                                    <option value="2">@lang('site.price_kart')</option>
                                </select>
                                <input type="text" name="sum_booking" value="{{ $count }}" style="display: none;">
                                <input type="text" name="min" value="{{ $min }}" style="display: none;">
                                <input type="text" name="priceAmount"
                                       value="@if(isset($priceOrderDiscount) && $priceOrderDiscount > 0 || isset($customer->skidka) && $customer->skidka == 100 ){{ $priceOrderDiscount }} @else {{ $priceOrder }} @endif"
                                       style="display: none;">
                                <div class="">
                                    <label>@lang('site.primitka')</label>
                                    <textarea name="info" class="form-control"></textarea>
                                </div>
                                <p><br>
                                    <input type='text' value="{{ $reserv }}" name="idreserv" style="display: none"/>
                                    <input id="b1" type='text' value="{{ $orderIds }}" name="id" style="display: none"/>
                                    <input id="b1" type='submit' value="Закрити"   class="btn btn btn-lg btn-warning active"/>
                                    </form>
                    </div>
                </div>
            </div>

            <div class="col-xs-12">
                <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                    Price Log Minutes
                </a>
                <div class="collapse" id="collapseExample">
                    <div class="card card-body">
                        @if($LogData)
                            @foreach($LogData['price'] as $key=>$price)
                                <dl class="dl-horizontal">
                                    <dt>
                                        {{$key+1}}-
                                        @if($LogData['minutes'][$key])
                                            <b>{{$LogData['minutes'][$key]}}</b>
                                        @endif
                                    </dt>
                                    <dd>{{$price}} грн.</dd>
                                </dl>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection