@extends('layouts.app')
@section('content')
    @php
        App::setLocale(session('lng'));
    @endphp
    <section class="content">
        <div class="s-order">
            <div class="container-fluid">
                <div class="row flex-container">
                    <div class="col-md-6 left-side-block">
                        <div class="order-number">
                            <h1>@lang('orders.order') #{{$id}}</h1>
                            <cart order_id="{{$id}}"></cart>
                        </div>
                        <div class="total-order">
                            <div class="textarea-wrapper">
                                <textarea id="comment" class="wishes-to-order"
                                          placeholder="@lang('orders.text') "></textarea>
                            </div>
                            <total-order></total-order>
                            <button-block  order_id="{{$id}}"></button-block>
                        </div>
                    </div>
                    <div class="col-md-6 order-menu">
                        <menu-order></menu-order>
                        <search></search>
                        <products order_id="{{$id}}"></products>
                        <categories></categories>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <clearorder-modal></clearorder-modal>
    <pay-modal order_id="{{$id}}"></pay-modal>
    <sms-modal order_id="{{$id}}"></sms-modal>
    <sms-code order_id="{{$id}}"></sms-code>
@endsection