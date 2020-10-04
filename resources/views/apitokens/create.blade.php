@extends('layouts.app')
@section('content')
    <div class="user">
        <div class="row">
            <div class="col-xs-12">
                <div class="user__title">
                    <h2>@lang('site.CreateApi')</h2>
                </div>

            </div>
            <div class="col-lg-8">
                <form method="post" action="{{ action('ApitokenController@store') }}">
                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                    <div class="form-group">
                        <label>@lang('site.ServiceApi')</label>
                        <input class="form-control" type="text" name="service" required>
                    </div>
                    <button class="btn btn-primary"  type="submit" ><i class="fa fa-plus"></i></button>
                </form>
            </div>
        </div>
    </div>
@endsection