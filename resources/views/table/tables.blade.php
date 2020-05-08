@extends('layouts.app')
@php
    App::setLocale(session('lng'));
@endphp
@section('content')
    <div class="row ConteerRowTable">

        <div class="col-sm-7 widthNone">
            <div class="board_block">
                <div id="opentableTitle" class="hidden board_title">@lang('table.opentable')</div>
                <open-table></open-table>
            </div>
            <div class="board_block">
                <div  id="freetableTable" class="hidden board_title">@lang('table.exittable')</div>
                <free-table></free-table>
            </div>
        </div>

        <div  class="col-xs-5 widthFull">
            <close-table></close-table>
        </div>
        <table-add></table-add>
    </div>
    <sms-modal order_id="-1"></sms-modal>
    <sms-code order_id="table"></sms-code>
    <pay-modal order_id="table"></pay-modal>
@endsection