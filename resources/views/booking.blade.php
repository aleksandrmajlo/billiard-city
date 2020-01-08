@extends('layouts.app')
@php
  App::setLocale(session('lng'));
@endphp
@section('content')
  <section class="content-header">
    <h1>
      @lang('site.reservations')
    </h1>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <a href="{{route('reservTable')}}" type="button" class="btn btn-lg btn-block btn-success" style="width: 50%;">+ @lang('site.add') </a>
          </div>
          <div class="box-body">
              {!! $calendar->calendar() !!}
              {!! $calendar->script() !!}
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection
