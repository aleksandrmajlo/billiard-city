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

           <h2> {{ $clients->name ?? '' }} {{ $clients->surname  ?? '' }}</h2>

            <br>{{ $clients->birthday ?? '' }}
            <br>{{ $clients->email ?? '' }}
            <br>{{ $clients->phone ?? '' }}
            <br>{{ $clients->description ?? '' }}
              <h4 style="color: orange">Розмір знижки: {{ $clients->skidka ?? '0' }} / @php
                      echo "Бiл: " . \App\Http\Controllers\DiscountController::userDiscount($clients->id,0,1);@endphp / @php echo "Бар: " . \App\Http\Controllers\DiscountController::userDiscount($clients->id,1,0); @endphp  %</h4>


              <h4 style="color: green">Витратив: {{ $ordersum  ?? '0' }} грн.</h4>

              @if(count($orders) > 0)
            <h2>Статистика</h2>

              <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                      <th>#</th>
                      <th>@lang('site.date_start')</th>
                      <th>@lang('site.summa')</th>
                      <th style="width: 210px"> </th>
                  </tr>
                  </thead>
                  <tbody>

                  @foreach($orders as $order)
                      <tr>
                          <td>{{ $order->id }}</td>

                          <td>{{  \Carbon\Carbon::parse($order->start)->format('d-m-Y H:i')}}</td>

                          <td><b>{{ $order->amount }} ₴</b></td>
                          <td>
                              <a href="/info/{{ $order->id }}" > >>></a>
                          </td>
                      </tr>
                  @endforeach
                  </tbody>
              </table>
                  @endif
          </div>



        </div>
      </div>
    </div>
  </section>
@endsection
