@extends('layouts.app')
@php
  App::setLocale(session('lng'));
@endphp
@section('content')

  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
            <h1>#{{ $reserv->id }}</h1>
            <b>Початок:</b> {{ $reserv->booking_from }}
            <br><b>Кінець:</b> {{ $reserv->booking_before }}


            @if($reserv->id_customers)
              <h2>@lang('site.client')</h2>
              @php
                $customer = App\Customer::where('id', $reserv->id_customers)->firstOrFail();
              @endphp
              {{$customer->name ?? "Гость"}} {{$customer->surname }} <h5>{{$customer->phone }}
            @endif


           @if(isset($reserv->a_guest))
                  <h2>Гость</h2>
                   {{ $reserv->a_guest ?? '' }}
           @endif

          </div>

          <form action="{{ url('book-delite' , $reserv->id ) }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('delete') }}
            <button class="btn btn=-lg btn-block btn-primary  btn-danger" type="submit">Зняти бронь</button>
          </form>
        </div>
      </div>
    </div>
  </section>
@endsection