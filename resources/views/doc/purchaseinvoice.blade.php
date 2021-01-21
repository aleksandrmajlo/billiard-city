@extends('layouts.app')
@section('content')
    <div class="user">
        <div class="user__title">
            <h2>@lang('purchaseinvoice.title') #{{$purchaseinvoice->id}}  </h2>
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
                        <a class="worker__button worker__export" href="/doc/purchaseinvoice/export/{{$purchaseinvoice->id}}">
                            <img src="/img/export.png" alt="colendar">
                            <span>@lang('act.excel')</span>
                        </a>
                        <doc-print id="print" header="@lang('act.act') #{{$purchaseinvoice->id}}  {{$purchaseinvoice->created_at}}"></doc-print>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @lang('site.SortThis'):
                <b>
                    @if($ActSortOrder=="cat")
                        @lang('site.Category')
                    @elseif($ActSortOrder=="title")
                        @lang('act.name')
                    @elseif($ActSortOrder=='type')
                        @lang('act.type')
                    @endif
                </b>
                -
                <b>
                    @if($ActSortOrder=='type')
                        @if($ActSortOrderType=='act')
                            @lang('act.product') @lang('site.SortThisAsc2')
                        @else
                            @lang('act.ingredient')  @lang('site.SortThisAsc2')
                        @endif
                    @else
                        @if($ActSortOrderType=='act')
                            @lang('site.SortThisAsc')
                        @else
                            @lang('site.SortThisDesc')
                        @endif
                    @endif

                </b>
            </div>
        </div>
        <div class="user_table acts__table" id="print">
            <table>
                <tr class="td-one">
                    <td>
                        @lang('act.name')
                        <form style="display:none;" action="{{ route('setCategoryDocSortOrder') }}" method="post">
                            <input type="hidden" name="sort" value="title">
                            <input type="hidden" name="type" value="">
                            {{csrf_field()}}
                        </form>
                        @if($ActSortOrderType=="desc")
                            <a href="" data-type="act" class="DocSortOrder categoryDocSortOrderASC">
                                <img src="/img/arrey.png" alt="arrey">
                            </a>
                        @else
                            <a href="" data-type="desc" class="DocSortOrder categoryDocSortOrderDESC">
                                <img src="/img/arrey.png" alt="arrey">
                            </a>
                        @endif
                    </td>
                    <td>
                        @lang('act.cat')
                        <form style="display:none;" action="{{ route('setCategoryDocSortOrder') }}" method="post">
                            <input type="hidden" name="sort" value="cat">
                            <input type="hidden" name="type" value="">
                            {{csrf_field()}}
                        </form>
                        @if($ActSortOrderType=="desc")
                            <a href="" data-type="act" class="DocSortOrder categoryDocSortOrderASC">
                                <img src="/img/arrey.png" alt="arrey">
                            </a>
                        @else
                            <a href="" data-type="desc" class="DocSortOrder categoryDocSortOrderDESC">
                                <img src="/img/arrey.png" alt="arrey">
                            </a>
                        @endif
                    </td>
                    <td>
                        @lang('act.type')
                        <form style="display:none;" action="{{ route('setCategoryDocSortOrder') }}" method="post">
                            <input type="hidden" name="sort" value="type">
                            <input type="hidden" name="type" value="">
                            {{csrf_field()}}
                        </form>
                        @if($ActSortOrderType=="desc")
                            <a href="" data-type="act" class="DocSortOrder categoryDocSortOrderASC">
                                <img src="/img/arrey.png" alt="arrey">
                            </a>
                        @else
                            <a href="" data-type="desc" class="DocSortOrder categoryDocSortOrderDESC">
                                <img src="/img/arrey.png" alt="arrey">
                            </a>
                        @endif
                    </td>
                    <td>
                        @lang('purchaseinvoice.sklad')
                    </td>
                </tr>
                @foreach($products as $product)
                    @include('doc.product')
                @endforeach
            </table>
        </div>
    </div>
@endsection