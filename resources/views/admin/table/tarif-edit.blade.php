@extends('layouts.app')

@section('content')
  @php
    App::setLocale(session('lng'));
  @endphp
  <section class="content-header">
    <h1>
      @lang('site.add')
    </h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
            <form method="POST" action="{{action('TariffController@edit', ['id' => $tarif->id])}}" enctype="multipart/form-data" />
            {{ csrf_field() }}
            <div class="row">
              <div class="col-xs-4">
                <select name="table" class="form-control" >
                  @foreach($tables as $table)
                    @if($table->id == $tarif->table_id)
                      <option value="{{ $table->id }}" selected>{{ $table->title }}</option>
                      @else
                    <option value="{{ $table->id }}">{{ $table->title }}</option>
                    @endif
                  @endforeach
                </select>
              </div>



              <div class="col-xs-4">
                <input type="number" name="price" value="{{ $tarif->price }}"   required class="form-control" placeholder="грн.">
              </div>
            </div>
            <div class="row" style="padding-top: 5px; padding-bottom: 5px;">
              <div class="col-xs-2">
                <select name="from_day_week"  class="form-control" required>
                  <option value="1" @if($tarif->from_day_week == 1)selected @endif>Пон.</option>
                  <option value="2" @if($tarif->from_day_week == 2)selected @endif>Вiв.</option>
                  <option value="3" @if($tarif->from_day_week == 3)selected @endif>Сер.</option>
                  <option value="4" @if($tarif->from_day_week == 4)selected @endif>Чет.</option>
                  <option value="5" @if($tarif->from_day_week == 5)selected @endif>П'ят.</option>
                  <option value="6" @if($tarif->from_day_week == 6)selected @endif>Суб.</option>
                  <option value="7" @if($tarif->from_day_week == 7)selected @endif>Нед.</option>
                </select>
              </div>
              <div class="col-xs-2">
                <input type="time" name="from_time" value="{{ $tarif->from_time }}" class="form-control" required>
              </div>

              <div class="col-xs-2">
                <select name="before_day_week" class="form-control" required>
                  <option value="1" @if($tarif->before_day_week == 1)selected @endif>Пон.</option>
                  <option value="2" @if($tarif->before_day_week == 2)selected @endif>Вiв.</option>
                  <option value="3" @if($tarif->before_day_week == 3)selected @endif>Сер.</option>
                  <option value="4" @if($tarif->before_day_week == 4)selected @endif>Чет.</option>
                  <option value="5" @if($tarif->before_day_week == 5)selected @endif>П'ят.</option>
                  <option value="6" @if($tarif->before_day_week == 6)selected @endif>Суб.</option>
                  <option value="7" @if($tarif->before_day_week == 7)selected @endif>Нед.</option>
                </select>
              </div>
              <div class="col-xs-2">
                <input type="time" name="before_time" value="{{ $tarif->before_time }}"  class="form-control" required >
              </div>
            </div>

            <div class="row">
              <div class="col-xs-2">
                <input type = 'submit' value = "@lang('site.edit')"  class="btn btn-primary active" />
              </div>
            </div>
            </form>
 </div>
</div>
</div>
</div>
</section>
@endsection
