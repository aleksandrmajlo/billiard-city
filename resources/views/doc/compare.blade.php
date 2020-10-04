@extends('layouts.app')
@php App::setLocale(session('lng'));@endphp
@section('content')
    <div class="user">
        <div class="user__title">
            <h2> @lang('act.compare') </h2>
        </div>
        @if($act1&&$act2)
            <div class="row mb-20">
                <div class="col-xs-6 col-md-6 col-xs-xs-12">
                    <div class="filter-block filter-worker heigthAuto">
                        <div class="filter-form">
                            <div class="row">
                                <div class="col-xs-5 col-xs-xs-xs-6">
                                    <label>ID</label>
                                </div>
                                <div class="col-xs-7 col-xs-xs-xs-6">
                                    <p> #{{$act1->id}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-5 col-xs-xs-xs-6">
                                    <label>@lang('act.worker')</label>
                                </div>
                                <div class="col-xs-7 col-xs-xs-xs-6">
                                    <p> {{$act1->user->name}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-5 col-xs-xs-xs-6">
                                    <label>@lang('act.date')</label>
                                </div>
                                <div class="col-xs-7 col-xs-xs-xs-6">
                                    <p>{{$act1->created_at}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 col-xs-xs-12">
                    <div class="filter-block filter-worker heigthAuto">
                        <div class="filter-form">
                            <div class="row">
                                <div class="col-xs-5 col-xs-xs-xs-6">
                                    <label>ID</label>
                                </div>
                                <div class="col-xs-7 col-xs-xs-xs-6">
                                    <p> #{{$act2->id}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-5 col-xs-xs-xs-6">
                                    <label>@lang('act.worker')</label>
                                </div>
                                <div class="col-xs-7 col-xs-xs-xs-6">
                                    <p> {{$act2->user->name}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-5 col-xs-xs-xs-6">
                                    <label>@lang('act.date')</label>
                                </div>
                                <div class="col-xs-7 col-xs-xs-xs-6">
                                    <p>{{$act2->created_at}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-xs-6 col-md-6 col-xs-xs-12 actOne">
                <div class="blue liken">
                    <a href="#win1" class="liken__buttom">
                        <img src="/img/liken.png" alt="colendar"><span>@lang('act.compareButton')</span>
                    </a>
                </div>
            </div>
            @if($act1&&$act2)
                <div class="col-xs-6 col-md-6 col-xs-xs-12 actOne">
                    <div class="blue worker">
                        <form method="get" id="searchform">
                            <input type="hidden" name="act1" value="{{request()->act1}}">
                            <input type="hidden" name="act2" value="{{request()->act2}}">
                            <input name="title" type="text" value="{{ app('request')->input('title') }}">
                            <button type="submit">
                                <img src="/img/search.png" alt="search">
                            </button>
                        </form>
                        <div class="worker-button">
                            <a class="worker__button worker__export"
                               href="/doc/compareexport?act1={{$act1->id}}&act2={{$act2->id}}">
                                <img src="/img/export.png" alt="colendar">
                                <span>@lang('act.excel')</span>
                            </a>
                            <doc-print id="print" header="@lang('act.act')"></doc-print>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        @if($act1&&$act2)
            <div class="user_table acts__table" id="print">
                <table>
                    <tr class="td-one">
                        <td>
                            @lang('act.name')
                        </td>
                        <td>
                            @lang('act.cat')
                            {{--                            <img src="/img/arrey.png" alt="arrey">--}}
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
                            @lang('act.sklad_smena') {{$act1->change->id}}
                        </td>
                        <td>
                            @lang('act.sklad_smena') {{$act2->change->id}}
                        </td>
                    </tr>
                    @if($typeDocSortOrder=="stocks")
                        @foreach($comp_results['stocks'] as $stock)
                            @include('doc.compare_stock')
                        @endforeach
                        @foreach($comp_results['ingredients'] as $ingredient)
                            @include('doc.compare_ingredient')
                        @endforeach
                    @else
                        @foreach($comp_results['ingredients'] as $ingredient)
                            @include('doc.compare_ingredient')
                        @endforeach
                        @foreach($comp_results['stocks'] as $stock)
                            @include('doc.compare_stock')
                        @endforeach
                    @endif
                    @if($showApparat)
                        <tr>
                            <td colspan="3" class="text-center">
                                @lang('act.kofeinyi_apparat')
                            </td>
                            <td>
                                {{$comp_results['kofeinyi_apparat'][0]}}
                            </td>
                            <td>
                                {{$comp_results['kofeinyi_apparat'][1]}}
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        @endif
    </div>
    @include('doc.compare_act_popup')
@endsection