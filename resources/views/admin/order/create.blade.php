@extends('layouts.app')
@section('content')
    @php
        App::setLocale(session('lng'));
    @endphp


    <section class="content-header">
        <h1>
            @lang('site.stvoritizakaz')
        </h1>
    </section>

    <section class="content">
        @if (session('status'))
            <div class="alert alert-danger">
                {{ session('status') }}
            </div>
        @endif
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <form method="POST" action="{{action('OrderController@store')}}">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-xs-12">
                                    <h2> @lang('site.client')</h2>
                                    <div class="radio">
                                        <label for="test1">
                                            <input type="radio" id="test1" class="radio_option2" name="radio-group" checked>
                                            @lang('site.gist')
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label for="test2">
                                            <input type="radio" id="test2" name="radio-group" class="radio_option">
                                            @lang('site.client')
                                        </label>
                                    </div>
                                    <div class="customersd" style="display: none; margin-bottom: 15px;">
                                        <div class="row">
                                            <div class="col-xs-7">
                                                <h4>@lang('site.client')</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-7">
                                                <input id="advanced-demoCreateOrder" class="form-control" type="text"
                                                       name="q" placeholder=" @lang('site.pochni')"
                                                       style="width:100%; max-width:600px;outline:0">
                                                <input type="text" id="customer2" style="display: none; "
                                                       name="customer">
                                                <div id="fb-root"></div>
                                            </div>
                                            <div class="col-xs-3" style="margin-top: 0px;">
                                                <a href="{{route('customerscreate')}}" type="button"
                                                   class="btn btn-block btn-success btn-lg"
                                                   style="width: 200px;">+ @lang('site.add')</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="raz">
                                <div class="col-xs-7 ">
                                    <label>Товар</label>
                                    <div class="formaProduct">
                                        <select class="form-control js-example-basic-single" name="product1" id="sel1">
                                            <option value="" selected>-</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->title }}
                                                    (склад: {{ $product->count }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-2 ">
                                    <label>@lang('site.kilkist')</label>
                                    <p><input type="number" value="1" min="0" max="1000" step="0.1" placeholder="1.0"
                                              name="count1" class="form-control" id="sel2" autocomplete="no"></p>
                                </div>
                            </div>
                            <div class="row" id="selectconnect">
                                <div class="col-xs-7" id="select_apend"></div>
                                <div class="col-xs-2" id="input_apend"></div>
                                <div class="col-xs-3 " id="del"
                                     style="margin-top: 1px; display: block; clear: inherit;   width: 100px;"></div>
                            </div>
                            <div class="row" style="position:relative; top: -15px;">
                                <div class="col-sm-6 col-lg-3">
                                    <div class="addproduct btn btn-block btn-success" id="2" style="margin-top: 26px;">
                                        + @lang('site.add') продукт
                                    </div>
                                </div>
                            </div>
                            <div class="" style="display: none">
                                <label>@lang('site.primitka')</label>
                                <textarea name="description"
                                          class="form-control">{{ $clients->description ?? '' }}
                                </textarea>
                            </div>

                            <div class="form-group">
                                <label>@lang('site.primitka')</label>
                                <textarea name="info" class="form-control"
                                          placeholder="для позначення з якого столу замовлення, або інше"></textarea>
                            </div>
                            <div class="form-group">
                                <input id="b1" type='submit' value="@lang('site.sozdat')"
                                       class="btn btn-lg btn-primary active"/>
                            </div>

                        </form>

                        <div id="formaphonsend">
                            <form method="POST" id="contactform">
                                {{ csrf_field() }}
                                <input type="number" name="phones" id="phons"
                                       style=" display: none; margin-top: 10px; width: 80%;  "
                                       placeholder="Телефон клієнта (09928830**)" value="" class="form-control gdd"
                                       required min="1000000">
                                <div class="rads" style="display: none">
                                    <input type="radio" id="contactChoice2"
                                           name="typesms" value="1" checked>
                                    <label for="contactChoice2">Дзвiнок</label>
                                    <br>
                                    <input type="radio" id="contactChoice3"
                                           name="typesms" value="2">
                                    <label for="contactChoice3">Sms</label>
                                    <br>
                                </div>
                                <input type='submit' id="p1" value="@lang('site.vidpraviti')"
                                       style="display: none; margin-top: 15px;" class="btn btn-lg btn-success"/>
                            </form>
                        </div>
                        <div id="msg"></div>

                        <div id="formaphonsend2" style="display: none">
                            <form method="POST" id="chechcode">
                                {{ csrf_field() }}
                                <input type="text" style="display: none" name="cod" id="cod"
                                       style="margin-top: 10px; width: 80%" placeholder="cod" value=""
                                       class="form-control">
                                <input type="text" name="codes" id="code" style="margin-top: 10px; width: 80%"
                                       placeholder="Запитайте код у клієнта" value="" class="form-control">
                                <input type='submit' value="@lang('site.per')" class="btn btn-lg btn-info"
                                       style="margin-top: 15px;"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
