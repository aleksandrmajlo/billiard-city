@extends('layouts.app')
@php
  App::setLocale(session('lng'));
@endphp
@section('content')
  <section class="content-header">
    <h1>
      Купон
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
        @endif
          <!-- /.box-header -->
          <div class="box-body">  <a  type="button" class="btn btn-lg btn-success  " data-order="1" data-toggle="modal" data-target="#modal-default" style="width: 200px;">+ @lang('site.add') купон</a> <br>
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>@lang('site.title')</th>
                <th style="width: 200px;"></th>

              </tr>
              </thead>
              <tbody>
                @foreach($cupons as $cupon)
                <tr>
                  <td>{{ $cupon->title }}</td>

                  <td><a  class="btn btn-block btn-primary btn-sm addCategory" style="margin-bottom: 7px;" data-toggle="modal" data-target="#modal-default2" data-id="{{ $cupon->id }}" data-title="{{ $cupon->title }}">@lang('site.edit')</a>
                    <form action="{{ url('category' , $cupon->id ) }}" method="POST">
                      {{ csrf_field() }}
                      {{ method_field('delete') }}
                      <button class="btn btn-block btn-primary btn-sm btn-danger" type="submit">@lang('site.del')</button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>

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

  <div class="modal fade" id="modal-default">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">@lang('site.add')</h4>
        </div>
        <div class="modal-body">
{{--
          <form method="POST" action="{{action('CategoryStockController@store')}}" enctype="multipart/form-data" />
          {{ csrf_field() }}
          <input type="text" name="title"  class="form-control" value="{{ $stock->title ?? '' }}" required />
          <br> <input type = 'submit'   class="btn btn-primary active" />
          </form>
--}}

        </div>
      </div>
    </div>    </div>
    <div class="modal fade" id="modal-default2">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('site.add')</h4>
          </div>
          <div class="modal-body">
{{--
            <form method="POST" action="{{action('CategoryStockController@edit')}}" enctype="multipart/form-data" />
            {{ csrf_field() }}
            <input type="text" style="display: none" name="id"  class="form-control idcatecory" value="1" required />
            <input type="text" name="title"  class="form-control titlecatecory" value="2" required />
            <br> <input type = 'submit'   class="btn btn-primary active" />
            </form>
--}}

          </div>
        </div>
      </div>
    </div>
@endsection
