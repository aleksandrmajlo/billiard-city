@extends('layouts.app')
@section('content')
    @php
        App::setLocale(session('lng'));
    @endphp
    <section class="content-header">
        <h1>
            @lang('site.add') бронь
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">

                        <form method="POST" action="{{action('ReservationController@reservTableCreate')}}"
                              enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-xs-6">
                                    <label>От</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type='date' name="booking_from" autocomplete="off"
                                               class="form-control datepickerMy " data-date-format="yyyy-mm-dd"
                                               data-time-format='hh:ii' data-timepicker="false"
                                               data-position="bottom left" required/>
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <label>@lang('site.vremya')</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class=" fa fa-clock-o"></i></span>
                                        <input type='time' name="booking_from_time" autocomplete="off"
                                               class="form-control " data-time-format='hh:ii' required/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <label>до</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type='date' name="booking_before" autocomplete="off"
                                               class="form-control  datepickerMy" data-date-format="yyyy-mm-dd"
                                               data-time-format='hh:ii' data-timepicker="false"
                                               data-position="bottom left" required/>
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <label>@lang('site.vremya')</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                        <input type='time' name="booking_before_time" autocomplete="off"
                                               class="form-control " data-time-format='hh:ii' required/>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="raz">
                                <div class="col-xs-12">
                                    <label>@lang('site.table')</label>
                                    <select class="form-control" name="table">
                                        @foreach($tables as $table)
                                            <option value="{{ $table->id }}">{{ $table->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-xs-12">
                                    <label>@lang('site.client')</label>
                                    <br>
                                    <input type="radio" id="test1" value="1" class="radio_option2" name="radiogroup"
                                           checked>
                                    <label for="test1">@lang('site.gist')</label>
                                    <div class="reservid">
                                        <input type='text' value="" placeholder="Ім'я або назва" name="reserv_name"
                                               class="form-control" style="margin-bottom: 10px;"/>
                                        <input type='text' value="" placeholder="Телефон" name="reserv_phone"
                                               class="form-control"/>
                                    </div>
                                    </p>
                                    <p>
                                        <input type="radio" id="test2" value="2" name="radiogroup" class="radio_option">
                                        <label for="test2">@lang('site.client')</label>
                                    </p>
                                    <div class="customersd" style="display: none; margin-bottom: 15px;">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <select class="form-control selectCustomer" name="customer">
                                                    <option value="-1">Вибрати</option>
                                                    @foreach($customers as $customer)
                                                        <option value="{{$customer->id}}" data-name="{{$customer->fullname}}">{{ $customer->phone }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-xs-6">
                                                 <p class="text-primary selectedName"></p>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="" style="display: none">
                                <label>@lang('site.primitka')</label>
                                <textarea name="description"
                                          class="form-control">{{ $clients->description ?? '' }}</textarea>
                            </div>
                             <br>
                             <input type='submit' value="@lang('site.sozdat')" class="btn btn-primary active"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
