@extends('layouts.app')
@php App::setLocale(session('lng'));@endphp
@section('content')
    <section class="content-header">
        <h1>
            @lang('act.open_smena')
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div id="alert-warning" class="alert alert-warning hidden" role="alert">
                    @lang('act.warning')
                </div>
            </div>
            <div class="col-xs-12">
                <form method="POST"  id="openOrder" action="{{action('ChangeController@create')}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="user_id" value="{{$user_id}}">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6 col-xs-6">
                                    <h5 class="box-title"> @lang('act.summa_start')</h5>
                                </div>
                                <div class="col-lg-2 col-xs-2">
                                    <input  value="0"
                                            type="number"
                                            name="summa_start"
                                            placeholder="@lang('act.summa_start')" class="form-control"
                                            required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <input type="submit" id="openOrderSubmit" class="btn btn-block btn-danger btn-lg "
                                   value="@lang('act.open_smena')">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection