@extends('layouts.app')
@php
  App::setLocale(session('lng'));
@endphp
@section('content')
  <section class="content-header">
    <h1>
      @lang('site.customer_base')
    </h1>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        @if (session('status'))
          <div class="alert alert-success">
            {{ session('status') }}
          </div>
        @endif
        <div class="box">
          <div class="box-header">
            <a href="{{route('customerscreate')}}" type="button" class="btn btn-lg btn-block btn-success" style="width: 50%;">+ @lang('site.add')
            </a>
          </div>
          <!-- /.box-header -->
          <div class="box-body"><div class="table-container">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>Id</th>
                <th>@lang('site.imya')</th>
                <th>@lang('site.birthday')</th>
                <th>E-mail</th>
                <th>Телефон</th>
                <th>@lang('site.register')</th>
                <th>@lang('site.vitrata')</th>
                <th style="width: 100px;">@lang('site.znizhka') (бiльярдна)</th>
                <th style="width: 100px;">@lang('site.znizhka') (бар)</th>
                <th> </th>
              </tr>
              </thead>
              <tbody>
                @foreach($clients as $client)
                <tr>
                  <td> {{ $client->id }}   </td>
                  <td><a href="/customer/{{ $client->id }} ">{{ $client->name }}  {{ $client->surname }}</a></td>
                  <td>{{ $client->birthday }}</td>
                  <td>{{ $client->email }}</td>
                  <td>{{ $client->phone }}</td>
                  <td>{{ $client->created_at }}</td>
                  <td>
                    @php
                      $sumClints = App\Order::where('customer_id', $client->id)
                      ->where('closed', '!=', 'null')
                      ->sum('amount');
                    @endphp
                    {{ $sumClints ?? 0  }} грн.
                  </td>
                  <td> {{ $client->skidka }}  </td>
                  <td> {{ $client->skidka_bar ?? '0'}}  </td>
                  <td>
                    <a href="/customers-edit?id={{ $client->id }}" class="btn btn-block btn-primary btn-sm">@lang('site.edit')</a>
                    @if(Auth::check() && \Auth::user()->hasRole('admin'))
                    <form action="{{ url('/customers' , $client->id ) }}" method="POST">
                      {{ csrf_field() }}
                      {{ method_field('delete') }}
                      <button style="margin-top: 7px;" class="btn btn-block btn-primary btn-sm btn-danger" type="submit">@lang('site.del')</button>
                    </form>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            </div>
            {{ $clients->links() }}
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
