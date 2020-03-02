@extends('layouts.app')
@php
  App::setLocale(session('lng'));
@endphp
@section('content')
  <section class="content-header">
    <h1>
      @lang('site.table')
    </h1>
  </section>

  <section class="content">

    @if (session('status'))
      <div class="alert alert-success">
        {{ session('status') }}
      </div>
    @endif

    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <a href="{{route('tablecreate')}}" type="button" class="btn btn-lg btn-success" style="width: 200px;">+ @lang('site.add')</a>
          </div>
          <div class="box-body">
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <th style="width: 30%">@lang('site.title')</th>
                        <th style="width: 20%">Мінімальний вартість у відкритому столі (грн.)</th>
                        <th style="width: 20%">Мінімальний вартість у відкритому столі н!ч(грн.)</th>
                        <th style="width: 20%">Безкоштовно закривати стіл до (хв.)</th>
                        <th style="width: 10%">Socket</th>
                        <th style="width:  20%;"></th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($tables as $table)
                        <tr>
                          <td>{{ $table->title }} (#{{ $table->id }})</td>
                          <td>{{ $table->max_min ?? '-' }}</td>
                          <td>{{ $table->max_min_night ?? '-' }}</td>
                          <td>{{ $table->min_min ?? '-'  }}</td>
                          <td>{{ $table->socket ?? '-'  }} {{ $table->port ?? '-'  }} </td>
                          <td style="width: 300px;min-width: 200px;">
                            <form action="{{ url('table' , $table->id ) }}" method="POST" style="float: left">
                              {{ csrf_field() }}
                              {{ method_field('delete') }}
                              <button class="btn btn-danger" type="submit">@lang('site.del')</button>
                            </form>
                            <a  type="button" data-id="{{$table->id}}"
                                data-max="{{$table->max_min}}"
                                data-max_min_night="{{$table->max_min_night}}"
                                data-min="{{$table->min_min}}"
                                data-socket="{{$table->socket}}"
                                data-number="{{$table->number}}"
                                data-image="{{$table->image}}"
                                data-rele="{{$table->port}}" class="addTable btn btn-default pull-right" data-toggle="modal"
                                data-target="#modal-default" style="float: right">Настроить</a>
                            </td></td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                </div>
            {{ $tables->links() }}
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
              <form method="POST" action="{{action('TableController@edit')}}" enctype="multipart/form-data" >
              {{ csrf_field() }}
                <input type="number" name="id"  class="form-control" id="table_id" style="display: none;"  />
                <label>Мінімальний вартість у відкритому столі (грн.) </label>
                <input type="number" name="max_min"  class="form-control"  id="table_max" value="{{ $table->max_min }}"  />

                 <label>Мінімальний вартість у відкритому столі н!ч(грн.) </label>
                 <input type="number" name="max_min_night"  class="form-control"  id="table_max_min_night" value="{{ $table->max_min_night }}"  />

                 <label>Безкоштовно закривати стіл до (хв.) </label>
                 <input type="number" name="min_max"  class="form-control"  id="table_min"  value="{{ $table->min_min }}"  />

              <label>Socket</label>
              <select class="form-control" name="socket" id="socket">
                  <option value="socket1" @if($table->socket == 'socket1') selected @endif>socket 1</option>
                  <option value="socket2" @if($table->socket == 'socket2') selected @endif>socket 2</option>
              </select>


              <label>№ реле</label>
              <input type="number" name="port"  class="form-control"  id="rele"  value="{{ $table->port }}"  />


              <div class="form-group">
                  <div >
                        <img  style="margin-top:20px;"  id='conteerTableImage' class="hidden img-responsive" src="{{$table->image}}" >
                  </div>
                <label>Icon</ladel>
                <input type="file" name='image' class="form-control" >
              </div>

              <div class="form-group">
                <label>Number</ladel>
                <input type="number"   id="table_number"  required min="1" name='number' class="form-control" value="{{ $table->number }}" >
              </div>

              <input type = 'submit'   class="btn btn-primary active" />

            </form>
          </div>
        </div>
      </div>
    </div>

  </section>
@endsection
