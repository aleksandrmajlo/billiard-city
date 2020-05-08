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
                    <div class="box-body"><h1>
                            @lang('site.zakaz') #{{ $orderId }}
                        </h1>
                        <table id="example1" class="table table-bordered table-striped" style="width: 60%;">
                            <thead>
                            <tr>
                                <th>Товар</th>
                                <th>Ціна</th>
                                <th>Шт.</th>
                                <th>@lang('site.summa')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>
                                        @php
                                            $prduct = App\Stock::where('id', $product->product_id)->firstOrFail();
                                        @endphp
                                        {{$prduct->title }}
                                    </td>
                                    <td> {{ $prduct->price }} грн.</td>
                                    <td> {{ $product->count }} </td>
                                    <td>
                                        @php
                                            $pricesum[] = $product->count * $prduct->price;
                                        @endphp
                                        {{ $product->count * $prduct->price}} грн.
                                    </td>
                                </tr>
                            @endforeach
                            @if(isset($schettable) && $schettable == 1)
                                <tr>
                                    <td>
                                        @php
                                            $table = App\Table::where('id', $orderId2->table_id)->firstOrFail();
                                        @endphp
                                        {{$table->title }}
                                    </td>
                                    <td></td>
                                    <td>{{ $min }} мiн</td>
                                    <td>{{ $summabill }} ₴</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        <h4>Початок: {{ $orderId2->start }}
                            <br>Кінець: {{ $orderId2->closed ?? '-' }}
                        </h4>
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
                                        <td style="border: 1px solid silver; padding: 5px;">{{$paus->start_pause}}
                                        </td>

                                        <td style="border: 1px solid silver; padding: 5px;"> {{$paus->end_pause ?? $orderId2->closed }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif

                        @if($schettable != 1)
                            @php
                                $customer = App\Customer::where('id', $orderId2->customer_id)->first();
                            @endphp

                            @if(isset($customer))
                                <h2>{{$customer->name  }}  {{$customer->surname  }}
                                    <h4 style="color: green">Знижка: {{$customer->skidka_bar  }}%</h4>
                                    <br>
                                    <h3>
                                        @lang('site.itogo') :
                                        @if($orderId2->type_bar==1)
                                            {{$orderId2->amount}}
                                        @else
                                            {{$priceAmount = floor(array_sum($pricesum) -  (array_sum($pricesum) * $customer->skidka_bar / 100 ))}}
                                        @endif
                                        ₴
                                    </h3>
                            @else
                                        <h4 style="color: green">Знижка: 0%</h4>
                                        @lang('site.itogo'):
                                        @if($orderId2->type_bar==1)
                                            {{$orderId2->amount}}
                                        @else
                                            {{$priceAmount = floor(array_sum($pricesum))}}
                                        @endif

                                        ₴
                                    @endif
                                    @endif
                                    @if($schettable == 1)
                                        <h3>
                                            @lang('site.itogo'): {{ $orderId2->amount }} ₴
                                        </h3>
                                    @endif
                                    @if(!isset($schettable) && $schettable != 1)
                                        @php
                                            $customer = App\Customer::where('id', $orderId2->customer_id)->first();
                                        @endphp
                                        @if(isset($customer))
                                            <h2>{{$customer->name  }}  {{$customer->surname  }}
                                                <br>
                                                Сума:
                                                @if(isset($pricesum))@php echo array_sum($pricesum); @endphp  @endif ₴
                                            </h2>
                                            @if($orderId2->type_bar == 1)
                                                <br>Знижка:  {{$customer->skidka_bar  }}%
                                            @endif

                                            @if($orderId2->type_billiards == 1)
                                                <br>Знижка:  {{ $customer->skidka}}%
                                            @endif
                                        @else
                                            Гiсть
                                            <h4 style="color: green">Знижка: 0%</h4>
                                        @endif
                                        <hr>
                                        <h3>@lang('site.itogo') :
                                            @if(isset($pricesum))
                                                @php echo  floor(array_sum($pricesum)) - floor((array_sum($pricesum) * $customer->skidka_bar / 100 )) ; @endphp
                                                ₴.
                                            @endif
                                        </h3>
                                    @endif
                                    @if(!isset($schet))
                                        <form method="POST" action="{{action('OrderController@orderBarClosedOrder')}}">
                                            {{ csrf_field() }}
                                            <label>@lang('site.type_playment')</label>
                                            <select class="form-control" name="billing" id="sel1">
                                                <option value="1">@lang('site.price_nal')</option>
                                                <option value="2">@lang('site.price_kart')</option>
                                            </select>
                                            <input type="text" name="priceAmount" value="{{ $priceAmount }}"
                                                   style="display: none;">
                                            <div class="">
                                                <label>@lang('site.primitka')</label>
                                                <textarea name="info"
                                                          class="form-control">{{$orderId2->info}}</textarea>
                                            </div>
                                            <p><br>

                                                <input id="b1" type='text' value="{{ $orderId }}" name="id"
                                                       style="display: none"/>
                                                <input id="b1" type='submit' value="Закрити"
                                                       class="btn btn btn-lg btn-warning active"/>
                                        </form>
                                    @endif
                                    @if(isset($schet))
                                        @php
                                            $customer = App\Customer::where('id', $customerid)->first();
                                        @endphp
                                        @if(isset($customer))
                                            {{$customer->name  }}  {{$customer->surname  }}
                                            @if($orderId2->type_bar == 1)
                                                <br>Знижка:  {{$customer->skidka_bar  }}%
                                            @endif

                                            @if($orderId2->type_billiards == 1)
                                                <br>Знижка:  {{ $customer->skidka}}%
                                            @endif
                                        @else
                                            Гiсть
                                        @endif
                                        <hr>
                                        <form method="POST" action="{{action('OrderController@orderBarClosedOrder')}}"
                                              enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            <label>@lang('site.type_playment')</label>
                                            <br>
                                            @if ($orderId2->billing == 1)
                                                @lang('site.price_nal')
                                            @endif
                                            @if ($orderId2->billing == 2)
                                                @lang('site.price_kart')
                                            @endif
                                            <div class="">
                                                <label>@lang('site.primitka')</label>
                                                <textarea name="info"
                                                          class="form-control">{{$orderId2->info}}</textarea>
                                            </div>

                                        </form>

                            @endif


                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection