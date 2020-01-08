@extends('layouts.app')

@section('content')
  @php
    App::setLocale(session('lng'));
  @endphp


  <section class="content-header">
    <h1>
      Добавить клиента
    </h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">

            <form method="POST" action="{{action('CustomerController@store')}}" enctype="multipart/form-data" />
            {{ csrf_field() }}
            <label>Имя</label>
            <input type="text" name="name"  class="form-control" value="{{ $clients->name ?? '' }}" required />
            <label>Фамилия</label>
            <input type="text" name="surname"  class="form-control"  value="{{ $clients->surname  ?? '' }}" />
            <label>День рождения</label>
            <p><input type="text"  name="birthday"  id="datepicker" class="form-control" value="{{ $clients->birthday ?? '' }}" size="30" autocomplete="no"></p>
            <label>E-mail</label>
            <input type="text" name="email" value="{{ $clients->email ?? '' }}" class="form-control" />
            <label>Телефон</label>
            <input type="text" name="phone" value="{{ $clients->phone ?? '' }}" class="form-control phone_mask" autocomplete="no" required />
            <label>@lang('site.primitka')</label>
            <textarea name="description"  class="form-control" >{{ $clients->description ?? '' }}</textarea>
            <p><br><input type = 'submit' value = "Добавить"  class="btn btn-primary active" />
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
