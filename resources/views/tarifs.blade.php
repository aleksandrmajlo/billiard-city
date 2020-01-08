@extends('layouts.app')
@php
    App::setLocale(session('lng'));
@endphp
@section('content')
  <section class="content-header">
    <h1>
      @lang('site.add_tariff')
    </h1>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif @if(\Auth::user()->hasRole('admin'))
          <div class="box-body">
              <form method="POST" action="{{action('TariffController@store')}}" enctype="multipart/form-data" />
              {{ csrf_field() }}
              <div class="row">
                  <div class="col-xs-4">
                      <select name="table" class="form-control" >
                          @foreach($tables as $table)
                              <option value="{{ $table->id }}">{{ $table->title }}</option>
                          @endforeach
                      </select>
                  </div>

                  <div class="col-xs-4">
                      <input type="number" name="price"   required class="form-control" placeholder="грн." required>
                  </div>
              </div>
              <div class="row" style="padding-top: 5px; padding-bottom: 5px;">
                  <div class="col-xs-2">
                      <select name="from_day_week"  class="form-control" required>
                          <option value="1">Пон.</option>
                          <option value="2">Вiв.</option>
                          <option value="3">Сер.</option>
                          <option value="4">Чет.</option>
                          <option value="5">П'ят.</option>
                          <option value="6">Суб.</option>
                          <option value="7">Нед.</option>
                      </select>
                  </div>
                  <div class="col-xs-2">
                      <input type="time" name="from_time" class="form-control" required>
                  </div>

                  <div class="col-xs-2">
                      <select name="before_day_week" class="form-control" required>
                          <option value="1">Пон.</option>
                          <option value="2">Вiв.</option>
                          <option value="3">Сер.</option>
                          <option value="4">Чет.</option>
                          <option value="5">П'ят.</option>
                          <option value="6">Суб.</option>
                          <option value="7">Нед.</option>
                      </select>
                  </div>
                  <div class="col-xs-2">
                      <input type="time" name="before_time" class="form-control" required>
                  </div>
              </div>

              <div class="row">
                  <div class="col-xs-2">
                      <input type = 'submit' value = "@lang('site.add')"  class="btn btn-primary active" />
                  </div>
              </div>
              </form>
              @endif


              <div class="callout callout-info" style="margin-top: 20px;">

                  <p>@lang('site.potr') </p>
              </div>


          @foreach($tables as $table)
                  <h2>{{ $table->title }}  </h2>
                  <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                          <th>@lang('site.chas')</th>
                          <th>@lang('site.chas2')</th>
                          <th>@lang('site.cina')</th>
                          <td width="100px"> </td>
                      </tr>
                      </thead>


                  @foreach ($table->tariff as $tariff)<tr>

                            @if ($tariff->from_day_week == 1)
                              @php ($from_day_week = "Пн.")
                            @elseif ($tariff->from_day_week == 2)
                              @php ($from_day_week = "Вт.")
                            @elseif ($tariff->from_day_week == 3)
                              @php ($from_day_week = "Ср.")
                            @elseif ($tariff->from_day_week == 4)
                              @php ($from_day_week = "Чт.")
                            @elseif ($tariff->from_day_week == 5)
                              @php ($from_day_week = "Пят.")
                            @elseif ($tariff->from_day_week == 6)
                              @php ($from_day_week = "Суб.")
                            @elseif ($tariff->from_day_week == 7)
                              @php ($from_day_week = "Вск.")
                            @endif

                                @if ($tariff->before_day_week == 1)
                                    @php ($before_day = "Пн.")
                                @elseif ($tariff->before_day_week == 2)
                                    @php ($before_day = "Вт.")
                                @elseif ($tariff->before_day_week == 3)
                                    @php ($before_day = "Ср.")
                                @elseif ($tariff->before_day_week == 4)
                                    @php ($before_day = "Чт.")
                                @elseif ($tariff->before_day_week == 5)
                                    @php ($before_day = "Пят.")
                                @elseif ($tariff->before_day_week == 6)
                                    @php ($before_day = "Суб.")
                                @elseif ($tariff->before_day_week == 7)
                                    @php ($before_day = "Вск.")
                                @endif


                      <tr> <td>{{$from_day_week}} {{$tariff->from_time}}  </td>
                          <td>{{$before_day}} {{$tariff->before_time}} </td>
                          <td>{{$tariff->price}} грн.</td>

                          <td>
                              <a href="/tarif-edit/{{$tariff->id}}" class="btn btn-block btn-primary btn-sm" type="submit" style="margin-bottom: 7px;">@lang('site.edit')</a>
                              <form action="{{ url('tarif' , $tariff->id ) }}" method="POST">
                              {{ csrf_field() }}
                              {{ method_field('delete') }}
                              <button class="btn btn-block btn-primary btn-sm btn-danger" type="submit">@lang('site.del')</button>
                          </form></td>
                      </tr>
                        @endforeach
                  </table>
                  </div>
              @endforeach

          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->



@endsection
