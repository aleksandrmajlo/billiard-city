@extends('layouts.app')
@php
    App::setLocale(session('lng'));
@endphp
@section('content')
    <section class="content-header">
        <h1>
            @lang('printsetting.printsetting')
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
                        <div class="box-body">
                            <form method="POST" action="{{action('PrintController@update')}}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                                <div class="form-group">
                                    <label>IP</label>
                                    <input class="form-control" name="ip" value="{{$printsetting->ip}}">
                                </div>
                                <div class="form-group">
                                    <label>Port</label>
                                    <input class="form-control" name="port" value="{{$printsetting->port}}">
                                </div>
                                <input type="submit" style="color: white; width: 300px; margin: 20px;"
                                       class="btn btn-success btn-block btn-default btn-lg" value="  @lang('printsetting.update')">
                            </form>
                        </div>
                </div>
            </div>
        </div>
    </section>
@endsection