@extends('layouts.app')
@php App::setLocale(session('lng'));@endphp
@section('content')

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


@endsection