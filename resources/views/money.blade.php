@extends('layouts.app')
@php
  App::setLocale(session('lng'));
@endphp
@section('content')


  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <a href="{{route('tablecreate')}}" type="button" class="btn btn-lg btn-success" style="width: 200px;">- Зняти</a>
          </div>
          <div class="box-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th style="width: 1%">#</th>
                <th style="width: 20%">Бар</th>
                <th style="width: 20%">Більярдна</th>
                <th style="width:  20%;">Админ</th>
                <th style="width:  20%;">Зміна</th>
                <th style="width:  20%;"></th>
              </tr>
              </thead>
              <tbody>
                @foreach($moneys as $money)
                <tr>
                  <td>{{ $money->id }}</td>
                  <td>{{ $money->sum_bar }} {{ $money->use_id }}</td>
                  <td>{{ $money->sum_bil }}</td>
                  <td>{{ $money->admin }} @if($money->admin_type == 1) (Більярдна) @endif @if($money->admin_type == 2) (Бар)@endif</td>
                  <td>{{ $money->smena }}</td>
                  <td>{{ $money->created_at }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
            {{ $moneys->links() }}
          </div>
        </div>
      </div>
    </div>


    <div class="modal fade" id="modal-default">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>

          </div>
          <div class="modal-body">
            <form method="POST" action="{{action('TableController@edit')}}" enctype="multipart/form-data" />
            {{ csrf_field() }}
            <input type="number" name="id"  class="form-control" id="table_id" style="display: none;" required />
              <label>Мінімальний час у відкритому столі (хв.) </label>
            <input type="number" name="max_min"  class="form-control"  id="table_min" value="" required />
              <label>Безкоштовно закривати стіл до (хв.) </label>
              <input type="number" name="min_max"  class="form-control"  id="table_max"  value="" required />
            <br> <input type = 'submit'   class="btn btn-primary active" />
            </form>
          </div>
        </div>
      </div>
    </div>

  </section>
@endsection
