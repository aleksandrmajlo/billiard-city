@extends('layouts.app')
@php
    App::setLocale(session('lng'));
@endphp
@section('content')
    <section class="content-header">
        <h1>
            @lang('site.stock')
        </h1>
    </section>

    <section class="" style="margin-top: 20px;padding: 15px;">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <form method="get" action="{{action('StockController@index')}}">
                        <div class="row" style="padding: 10px;">
                            <div class=" col-12  col-xs-12">
                                <div class="col-6 col-xs-12 col-md-6 ">
                                    <input type="text" name="searchtitle" placeholder="Назва" class="form-control"
                                           style="min-width: 300px;">
                                </div>
                                <div class="col-12 col-xs-12 col-md-2 ">
                                    <div class="input-group">
                                        <select class="form-control" name="type">
                                            <option value="0">Категорiя</option>
                                            @foreach($categoryStocks as $categoryStock)
                                                <option value="{{ $categoryStock->id }}">{{  $categoryStock->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-xs-12 col-md-2 ">
                                    <div class="input-group">
                                        <input type="submit" style="color: white"
                                               class="btn btn-success btn-block btn-default btn-lg"
                                               value="@lang('site.poisk')">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">

                    @if(\Auth::user()->hasRole('admin'))
                        <div class="box-header">
                            <a href="{{route('stockcreate')}}" type="button" class="btn btn-lg btn-success  "
                               style="width: 200px;">+ @lang('site.add') товар</a>

                            <a type="button" class="btn btn-lg btn-success  " data-order="1" data-toggle="modal"
                               data-target="#modal-default" style="width: 200px;">+ @lang('site.add') рубрику</a>
                        </div>
                    @endif

                <!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>@lang('site.title')</th>
                                    <th>@lang('site.ingr')</th>
                                    <th>Категория</th>
                                    <th>@lang('site.cina')</th>
                                    <th>@lang('site.na_skladi')</th>
                                    <th>@lang('site.bezlimit')</th>
                                    <th>Доступно</th>
                                    @if(\Auth::user()->hasRole('admin'))
                                        <th width="100px;"></th>@endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{ $product->title }}</td>
                                        <td class="text-center">
                                            {{count($product->ingredients)}}
                                        </td>
                                        <td>{{ $product->categorySee->title}}</td>
                                        <td>{{ $product->price}} грн.</td>
                                        <td>
                                            @if($product->categorySee->id == $kofeinyi_apparat_category_id)
                                            @else
                                                {{ $product->count }}
                                            @endif

                                        </td>
                                        <td>@if($product->unlimited == 1) <i class="fa fa-plus-square"></i>@endif</td>
                                        <td>
                                            @if($product->published == 0)
                                                <span class="red">Не доступно</span>
                                            @endif
                                            @if($product->published == 1)
                                                <span class="green">Доступно</span>
                                            @endif
                                        </td>
                                        @if(\Auth::user()->hasRole('admin'))
                                            <td>
                                                <a href="/stock-create?id={{ $product->id }}&page=@if(isset($_GET['page'])){{ $_GET['page'] }}@endif&cat=@if(isset($product->categorySee->id)){{ $product->categorySee->id }}@endif"
                                                   class="btn btn-block btn-primary btn-sm"
                                                   style="margin-bottom: 7px;">@lang('site.edit')</a>
                                                <form action="{{ url('stock' , $product->id ) }}" method="POST">
                                                    {{ csrf_field() }}
                                                    {{ method_field('delete') }}
                                                    <button class="btn btn-block btn-primary btn-sm btn-danger"
                                                            type="submit">@lang('site.del')</button>
                                                </form>
                                            </td>@endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $products->appends($_GET)->links() }}

                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">@lang('site.add')</h4>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{action('CategoryStockController@store')}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <input type="text" name="title" class="form-control" value=""
                                   required/>
                        </div>
                        <input type='submit' class="btn btn-primary active"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
