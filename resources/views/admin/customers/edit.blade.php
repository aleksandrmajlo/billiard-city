@extends('layouts.app')
@php
  App::setLocale(session('lng'));
@endphp
@section('content')
  <section class="content-header">
    <h1>
      @lang('site.client')
    </h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">

            <form method="POST" action="{{action('CustomerController@update')}}" enctype="multipart/form-data" />

              {{ csrf_field() }}
            @if(isset($clients->id))
            <input type="text" name="id"  style="display: none" class="form-control" value="{{ $clients->id ?? '' }}" required />
            @endif
            <label>@lang('site.imya')</label>
            <input type="text" name="name"  class="form-control" value="{{ $clients->name ?? '' }}" required />
            <label>@lang('site.sername')</label>
            <input type="text" name="surname"  class="form-control"  value="{{ $clients->surname  ?? '' }}" />
            <label> @lang('site.birthday')</label>
            <input type='date' name="birthday" autocomplete="off" class="form-control datepicker-here" value="{{ $clients->birthday ?? '' }}"  data-date-format="yyyy-mm-dd"  data-timepicker="false" data-position="bottom left" />
            <label>E-mail</label>
            <input type="email" name="email" value="{{ $clients->email ?? '' }}" class="form-control" />
            @if(\Auth::user()->hasRole('admin'))
            <label>@lang('site.znizhka') (бiльярдна)</label>
            <input type="number" name="skidka" value="{{ $clients->skidka ?? '0' }}" class="form-control" />

              <label>@lang('site.znizhka') (бар)</label>
              <input type="number" name="skidka_bar" value="{{ $clients->skidka_bar ?? '0' }}" class="form-control" />
            @endif
          @if(!isset($clients->email)) <div style="">   @endif   @if(Auth::check() && \Auth::user()->hasRole('admin'))     <label>Телефон (0992883008)</label>


                <input type="text" id="ph" name="phone" value="{{ $clients->phone ?? '' }}" class="form-control" autocomplete="no"  required />
              @endif
              @if(!isset($clients->email)) </div>  @endif
            <label>@lang('site.primitka')
            </label>
            <textarea name="description"  class="form-control" >{{ $clients->description ?? '' }}</textarea>
            <p><br>
              <input type="submit" value="Редагувати" class="btn btn-lg  btn-success" />
            </form>

          </div>

        </div>
      </div>
    </div>
  </section>
@endsection
