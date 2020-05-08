@extends('layouts.app')
@php
    App::setLocale(session('lng'));
@endphp
@section('content')
    <div>
        <div class="user">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="user__title">
                        <h2> @lang('site.reservations')</h2>
                    </div>
                    <calendar-booking></calendar-booking>
                </div>
                <search-results></search-results>
            </div>
        </div>
        <read-booking></read-booking>
    </div>
@endsection
