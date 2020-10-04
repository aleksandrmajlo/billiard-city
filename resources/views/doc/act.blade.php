@extends('layouts.app')
@php App::setLocale(session('lng'));@endphp
@section('content')
    <div class="user">
        <div class="user__title">
            <h2>@lang('act.act') #{{$act->id}} </h2>
        </div>

        <div class="row">
            <div class="col-xs-6 col-md-7 col-xs-xs-12">
                <div class="filter-block filter-worker">
                    <div class="filter-form">
                        <div class="row">
                            <div class="col-xs-5 col-xs-xs-xs-6">
                                <label>@lang('act.worker')</label>
                            </div>
                            <div class="col-xs-7 col-xs-xs-xs-6">
                                <p> {{$act->user->name}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-5 col-xs-xs-xs-6">
                                <label>@lang('act.date')</label>
                            </div>
                            <div class="col-xs-7 col-xs-xs-xs-6">
                                <p>{{$act->created_at}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-6 col-md-5 col-xs-xs-12 actOne">
                <div class="blue worker">
                    <form method="get" id="searchform">
                        <input name="title" type="text" value="{{ app('request')->input('title') }}">
                        @if(request()->has('title'))
                            <a href="{{$urlFilter}}">@lang('act.reset')</a>
                        @endif
                        <button type="submit">
                            <img src="/img/search.png" alt="search">
                        </button>
                    </form>
                    <div class="worker-button">
                        <a class="worker__button worker__export" href="/doc/act/export/{{$act->id}}">
                            <img src="/img/export.png" alt="colendar">
                            <span>@lang('act.excel')</span>
                        </a>
                        <doc-print id="print" header="@lang('act.act') #{{$act->id}}  {{$act->created_at}}"></doc-print>
                    </div>
                </div>
            </div>

        </div>


        <div class="user_table acts__table" id="print">
            <table>
                <tr class="td-one">
                    <td>@lang('act.name')</td>
                    <td>@lang('act.cat')
                        <form style="display:none;" action="{{ route('setCategoryDocSortOrder') }}" method="post"
                              id="categoryDocSortOrderForm">
                            <input type="hidden" name="sort">
                            {{csrf_field()}}
                        </form>
                        @if($categoryDocSortOrder=="desc")
                            <a href="" data-sort="asc" class="categoryDocSortOrder categoryDocSortOrderDESC">
                                <img src="/img/arrey.png" alt="arrey">
                            </a>
                        @else
                            <a href="" data-sort="desc" class="categoryDocSortOrder categoryDocSortOrderASC">
                                <img src="/img/arrey.png" alt="arrey">
                            </a>
                        @endif
                    </td>
                    <td>

                        <form style="display:none;" action="{{ route('setTypeDocSortOrder') }}" method="post"
                              id="typeDocSortOrderForm">
                            <input type="hidden" name="type">
                            {{csrf_field()}}
                        </form>
                        @lang('act.type')
                        @if($typeDocSortOrder=='stocks')
                            <a href="" data-sort="ingredient" class="typeDocSortOrder typeDocSortOrderStocks">
                                <img src="/img/arrey.png" alt="arrey">
                            </a>
                        @else
                            <a href="" data-sort="stocks" class="typeDocSortOrder ">
                                <img src="/img/arrey.png" alt="arrey">
                            </a>
                        @endif
                    </td>
                    <td>
                        @lang('act.sklad')
                    </td>
                </tr>
                @if($typeDocSortOrder=="stocks")
                    @foreach($stocks as $stock_arr)
                        @foreach($stock_arr as $stock)
                            @include('doc.stock_sort')
                        @endforeach
                    @endforeach
                    @foreach($act->ingredients as $ingredient)
                        @include('doc.ingredient')
                    @endforeach
                @else
                    @foreach($act->ingredients as $ingredient)
                        @include('doc.ingredient')
                    @endforeach
                        @foreach($stocks as $stock_arr)
                            @foreach($stock_arr as $stock)
                                @include('doc.stock_sort')
                            @endforeach
                        @endforeach
                @endif
                @if($showApparat)
                    @include('doc.kofeinyiapparat')
                @endif
            </table>
        </div>
    </div>

    <!--
    <section class="content-header">
        <h1 class="mb-10">
            @lang('act.act') #{{$act->id}}  {{$act->created_at}}
            </h1>
        </section>
        <section class="filterConteer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-body">
                                <dl class="row">
                                    <dt class="col-sm-6">@lang('act.worker')</dt>
                                <dd class="col-sm-6">
                                    {{$act->user->name}}
            </dd>
            <dt class="col-sm-6">@lang('act.date')</dt>
                                <dd class="col-sm-6">
                                    {{$act->created_at}}
            </dd>
        </dl>
@include('doc.filter_act_compare')
            </div>
        </div>
    </div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">
<div class="row">
    <div class="col-xs-12 mt-10">
       <doc-print id="print" header="@lang('act.act') #{{$act->id}}  {{$act->created_at}}"></doc-print>
                    <a class="btn btn-info" target="_blank" href="/doc/act/export/{{$act->id}}">@lang('act.excel')</a>
                </div>
                <div class="col-xs-12 mt-10">
                    <div class="box">
                        <div class="box-body">
                            <div id="print">
                                <div class="table-responsive">
                                    <table id="ActsTable" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <td>
                                                @lang('act.name')
            </td>
            <td>
@lang('act.cat')
            </td>
            <td>
@lang('act.type')
            </td>
            <td>
@lang('act.sklad')
            </td>
        </tr>
        </thead>
        <tbody>
@if(request()->has('type'))
        @if(request()->type=='product')
            @foreach($act->stocks as $stock)
                @include('doc.stock')
            @endforeach
        @endif
        @if(request()->type=='ingredient')
            @foreach($act->ingredients as $ingredient)
                @include('doc.ingredient')
            @endforeach
        @endif
    @else
        @foreach($act->ingredients as $ingredient)
            @include('doc.ingredient')
        @endforeach
        @foreach($act->stocks as $stock)
            @include('doc.stock')
        @endforeach
    @endif
    @if($showApparat)
        @include('doc.kofeinyiapparat')
    @endif
            </tbody>
        </table>
    </div>
</div>

</div>
</div>
</div>
</div>
</div>
</section>
-->
@endsection