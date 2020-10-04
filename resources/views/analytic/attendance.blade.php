@extends('layouts.app')
@section('content')
<div class="user">
    <div class="row">
        <div class="col-sm-8 col-xs-12">
            <div class="analytics__title">
                <h2>@lang('site.analytics')
                    <span>@lang('site.analytic_attendance')</span></h2>
            </div>
            <attendanc-calendarmobile></attendanc-calendarmobile>
            <attendanc-analitic></attendanc-analitic>
        </div>
        <div class="col-sm-4 col-xs-12 ">
            <attendanc-calendar></attendanc-calendar>
        </div>
        <attendancfooter-analitic></attendancfooter-analitic>
    </div>
</div>
@endsection