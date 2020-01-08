@extends('layouts.app')
@php App::setLocale(session('lng'));@endphp
@section('content')
    <section class="content-header">
        <h1>
            @lang('act.close_smena')
        </h1>
    </section>


    <section class="content">
        <div class="row">

            <div class="col-xs-12">
                <form   method="POST" action="{{action('ChangeController@closeChange')}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{$id}}">

                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6 col-xs-6">
                                    <h5 class="box-title"> @lang('act.summa_end')</h5>
                                </div>
                                <div class="col-lg-2 col-xs-2">
                                    <input style="" value="0" type="number" name="summa_end"
                                           placeholder="@lang('site.summa')" class="form-control"
                                           required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <input type="submit"  class="btn btn-block btn-danger btn-lg "
                                   value="@lang('act.close_smena')">
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </section>
@endsection