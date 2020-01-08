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
          @if(isset($clients->email))
            <form method="POST" action="{{action('CustomerController@update')}}" enctype="multipart/form-data" />
          @else
              <form method="POST" action="{{action('CustomerController@store')}}" enctype="multipart/form-data" />
            @endif
              {{ csrf_field() }}
            @if(isset($clients->email))
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
          @if(!isset($clients->email)) <div style="display: none;">   @endif        <label>Телефон (0992883008)</label>


                <input type="text" id="ph" name="phone" value="{{ $clients->phone ?? '' }}" class="form-control" autocomplete="no"  required />
              @if(!isset($clients->email)) </div>  @endif
            <label>@lang('site.primitka')
            </label>
            <textarea name="description"  class="form-control" >{{ $clients->description ?? '' }}</textarea>
            <p><br><input type = 'submit' @if(!isset($clients->email))style="display: none;" id="createSend" value = "  @lang('site.add') " @else value = "Редагувати" @endif  class="btn btn-lg  btn-success" />
            </form>
            @if(!isset($clients->email))
            <div id="formaphonsend">
            <form method="POST" id="contactform"  />
             {{ csrf_field() }}

              <input type="text" name="phones" id="phons" style="margin-top: 10px; width: 80%" placeholder="Телефон  (09928830**)" value=""  class="form-control" required  >
{{--              <br><input type="checkbox" name="zvonok" value="5"> дзвінок<br>--}}

                <input type="radio" id="contactChoice2"
                       name="typesms" value="1" checked>
                <label for="contactChoice2">@lang('site.dzvinok')</label>
                <br>
                <input type="radio" id="contactChoice3"
                       name="typesms" value="2">
                <label for="contactChoice3">Sms</label>
                <br>

                <input type = 'submit'   name="send2" value = "@lang('site.vidpraviti')" style="margin-top: 15px;"  class="btn btn-lg btn-success" />

              </form>
            </div>
            <div id="msg"></div>

            <div id="formaphonsend2" style="display: none">
              <form method="POST" id="chechcode"  />
              {{ csrf_field() }}
                <input type="text" style="display: none" name="cod" id="cod" style="margin-top: 10px; width: 30%" placeholder="cod" value="" class="form-control">
              <input type="text" name="codes" id="code" style="margin-top: 10px; width: 80%" placeholder="Запитайте код у клієнта" value="" class="form-control">
              <input type = 'submit' value = "@lang('site.per')"  class="btn btn-lg btn-info" style="margin-top: 15px;" />
              </form>
            </div>

            @endif
          </div>



        </div>
      </div>
    </div>
  </section>
@endsection
