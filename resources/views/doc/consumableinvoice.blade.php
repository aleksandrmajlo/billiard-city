@extends('layouts.app')
@section('content')

    <div class="user">
        <div class="user__title">
            <h2> @lang('сonsumableinvoice.titleOne') #{{$consumableinvoice->id}}  </h2>
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
                                <p> {{$consumableinvoice->user->name}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-5 col-xs-xs-xs-6">
                                <label>@lang('act.date')</label>
                            </div>
                            <div class="col-xs-7 col-xs-xs-xs-6">
                                <p>{{$consumableinvoice->created_at}}</p>
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
                        <a class="worker__button worker__export" href="/doc/consumableinvoice/export/{{$consumableinvoice->id}}">
                            <img src="/img/export.png" alt="colendar">
                            <span>@lang('act.excel')</span>
                        </a>
                        <doc-print id="print" header="@lang('act.act') #{{$consumableinvoice->id}}  {{$consumableinvoice->created_at}}"></doc-print>
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
                        @lang('сonsumableinvoice.count')
                    </td>
                    <td>
                        @lang('сonsumableinvoice.price')
                    </td>

                    <td>
                        @lang('сonsumableinvoice.skidka')
                    </td>

                    <td>
                        @lang('сonsumableinvoice.allprice')
                    </td>
                </tr>
                @foreach($productRes as $products)
                    @foreach($products as $k=>$product)
                        <tr>
                            <td>{{$product['title']}}</td>
                            <td>{{$product['count']  }}</td>
                            <td>{{$product['price']  }} грн.</td>
                            <td>{{$k}} %</td>
                            <td>{{$product['total'] }} грн.</td>
                        </tr>
                    @endforeach
                @endforeach
                <tr>
                    <td colspan="2">@lang('сonsumableinvoice.allpriceProducts')</td>
                    <td colspan="2">{{$total}} грн.</td>
                </tr>
            </table>
        </div>


    </div>


{{--

    <section class="content-header">
        <h1 class="mb-10">
            @lang('сonsumableinvoice.titleOne') #{{$consumableinvoice->id}} {{$consumableinvoice->created_at}}
        </h1>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <doc-print id="ActsTable" header=""></doc-print>
                    <a class="btn btn-info" target="_blank" href="/doc/consumableinvoice/export/{{$consumableinvoice->id}}">@lang('act.excel')</a>
                </div>
                <div class="col-xs-12">
                     <h4 >Категории</h4>
                </div>
                <div class="col-xs-12">
                    <form method="GET" class="form-inline" >
                            <div class="form-group">
                                <select class="form-control" name="category">
                                    @foreach ($categories as $category)
                                        <option @if ($category_id&&$category_id==$category->id)
                                            selected
                                        @endif value="{{$category->id}}">{{$category->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="btn btn-primary" type="submit">Фильтрировать</button>
                        <a class="btn" href="{{$this_url}}">Очистить</a>
                        </div>
                    </form>
                </div>
                <br>
                <div class="col-xs-12">
                    <div class="box">
                        @if($productRes)
                            <div class="box-body">
                                <div class="table-container">
                                    <div id="print">
                                        <div class="table-responsive">

                                            <table id="ActsTable" class=" DataTable table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <td>
                                                        @lang('act.name')
                                                    </td>
                                                    <td>
                                                        @lang('сonsumableinvoice.count')
                                                    </td>

                                                    <td>
                                                        @lang('сonsumableinvoice.price')
                                                    </td>

                                                    <td>
                                                        @lang('сonsumableinvoice.skidka')
                                                    </td>

                                                    <td>
                                                        @lang('сonsumableinvoice.allprice')
                                                    </td>

                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($productRes as $products)
                                                    @foreach($products as $k=>$product)
                                                        <tr>
                                                            <td>{{$product['title']}}</td>
                                                            <td>{{$product['count']  }}</td>
                                                            <td>{{$product['price']  }} грн.</td>
                                                            <td>{{$k}} %</td>
                                                            <td>{{$product['total'] }} грн.</td>
                                                        </tr>

                                                    @endforeach
                                                @endforeach
                                                </tbody>
                                                <thead>
                                                <tr>
                                                    <td colspan="2">@lang('сonsumableinvoice.allpriceProducts')</td>
                                                    <td colspan="2">{{$total}} грн.</td>
                                                </tr>
                                                </thead>
                                            </table>

                                            <table></table>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

--}}



@endsection