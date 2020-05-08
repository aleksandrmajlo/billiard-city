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
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">

                        <form method="POST" action="{{action('OrderController@storeOrderTable')}}"
                              enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row" id="raz">
                                <div class="col-xs-12">

                                    <select class="form-control" name="table">
                                        @foreach($tables as $table)
                                            <option value="{{ $table->id }}">{{ $table->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xs-12" style="margin-top: 10px;">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <label>@lang('site.client')</label>
                                            <br>
                                            <input type="radio" id="test1" class="radio_option2" name="radio-group"
                                                   checked>
                                            <label for="test1">@lang('site.gist')</label>
                                            </p>
                                            <p>
                                                <input type="radio" id="test2" name="radio-group" class="radio_option">
                                                <label for="test2">@lang('site.client')</label>
                                            </p>
                                            <div class="customersd" style="display: none; margin-bottom: 15px;">
                                                <div class="row">
                                                    <div class="col-xs-6">

                                                        <select class="form-control selectCustomer" name="customer">
                                                            <option value="-1">Вибрати</option>
                                                            @foreach($customers as $customer)
                                                                <option value="{{$customer->id}}"
                                                                        data-name="{{$customer->fullname}}">{{ $customer->phone }}</option>
                                                            @endforeach
                                                        </select>
                                                        <p class="text-primary selectedName"></p>
                                                    <!--
                                                    <input id="advanced-demo" class="form-control" type="text" name="q"
                                                           placeholder=" @lang('site.pochni')"
                                                           style="width:100%; max-width:600px;outline:0">
                                                    <div id="fb-root">

                                                    </div>
                                                    <input type="text" id="customer2" style="display: none; "
                                                           name="customer">
                                                     -->

                                                    </div>
                                                    <div class="col-xs-3" style="margin-top: 0px;">
                                                        <a href="{{route('customerscreate')}}" type="button"
                                                           class="btn btn-block btn-lg btn-success"
                                                           style="width: 200px;">+ @lang('site.add')</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row" style="margin-top: 19px; display: none;">
                                <div class="col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type='text' value="{{ \Carbon\Carbon::now() }}" name="booking_from"
                                               autocomplete="off" class="form-control datepicker-here"
                                               data-date-format="yyyy-mm-dd" data-time-format='hh:ii'
                                               data-timepicker="true"
                                               data-position="bottom left"/>
                                    </div>
                                </div>
                            </div>
                            <div class="" style="display: none">
                                <label>@lang('site.primitka')</label>
                                <textarea name="description"
                                          class="form-control">{{ $clients->description ?? '' }}</textarea>
                            </div>
                            <p><br><input id="b1" type='submit' value="@lang('site.sozdat')"
                                          class="btn btn-primary active"/>
                        </form>
                        <div id="formaphonsend">
                            <form method="POST" id="contactform">
                                {{ csrf_field() }}
                                <input type="number" name="phones" id="phons"
                                       style=" display: none; margin-top: 10px; width: 30%;  "
                                       placeholder="Телефон клієнта (09928830**)" value="" class="form-control gdd"
                                       min="1000000">
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
                                   style="margin-top: 10px; width: 30%" placeholder="cod" value="" class="form-control">
                            <input type="text" name="codes" id="code" style="margin-top: 10px; width: 30%"
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
