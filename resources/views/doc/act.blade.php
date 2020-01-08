@extends('layouts.app')
@php App::setLocale(session('lng'));@endphp
@section('content')
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
@endsection