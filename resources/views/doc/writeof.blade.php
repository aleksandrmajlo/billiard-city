@extends('layouts.app')
@section('content')
    <div class="user">
        <div class="user__title">
            <h2>@lang('writeof.title') #{{$purchaseinvoice->id}}  </h2>
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
                                <p> {{$purchaseinvoice->user->name}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-5 col-xs-xs-xs-6">
                                <label>@lang('act.date')</label>
                            </div>
                            <div class="col-xs-7 col-xs-xs-xs-6">
                                <p>{{$purchaseinvoice->created_at}}</p>
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
                            <a href="{{$this_url}}">@lang('act.reset')</a>
                        @endif
                        <button type="submit">
                            <img src="/img/search.png" alt="search">
                        </button>
                    </form>
                    <div class="worker-button">
                        <a class="worker__button worker__export" href="/doc/writeof/export/{{$purchaseinvoice->id}}">
                            <img src="/img/export.png" alt="colendar">
                            <span>@lang('act.excel')</span>
                        </a>
                        <doc-print id="print" header="@lang('act.act') #{{$purchaseinvoice->id}}  {{$purchaseinvoice->created_at}}"></doc-print>
                    </div>
                </div>
            </div>
        </div>
        <div class="user_table acts__table" id="print">
            <table>
                <tr class="td-one">
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
                        @lang('writeof.sklad')
                    </td>
                    <td>
                        Причина
                    </td>
                </tr>
                @foreach($purchaseinvoice->stocks as $stock)
                    @include('doc.stock')
                @endforeach
                @foreach($purchaseinvoice->ingredients as $ingredient)
                    @include('doc.ingredient')
                @endforeach
            </table>
        </div>
    </div>
@endsection
