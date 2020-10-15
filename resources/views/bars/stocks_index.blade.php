@extends('layouts.app')
@section('content')
    <div class="user">
        <div class="row">
            <div class="col-xs-6 col-xs-xs-12">
                <div class="user__title">
                    <h2>  @lang('site.stock')</h2>
                    @include('bars.productfilter')
                </div>
            </div>
        </div>
        <div class="blue liken">
            <a href="{{route('stocks.create')}}" class="liken__buttom">
                <img src="/img/liken.png" alt="colendar"><span>@lang('site.add')</span>
            </a>
        </div>
        @if(Session::has('delete'))
            <div class="alert-info alert">
                @lang('site.delit')
            </div>
        @endif
        @if(Session::has('ProductAdd'))
            <div class="alert-info alert">
                @lang('site.ProductAdd')
            </div>
        @endif
        <div class="user_table acts__table">

            <table class="tableCustomers">
                <tr>
                    <td>ID</td>
                    <td>@lang('site.title')</td>
                    <td>Категория</td>
                    <td>@lang('site.na_skladi')</td>
                    <td>@lang('site.cina')</td>
                    {{--                    <td>@lang('site.ingr')</td>--}}
{{--                    <td>@lang('site.bezlimit')</td>--}}
{{--                    <td>Доступно</td>--}}
                    @if(\Auth::user()->hasRole('admin'))
                        <td>@lang('site.ProductAction')</td>
                    @endif
                </tr>
                @foreach($products as $product)
                    <tr>
                        <td>
                            @if(\Auth::user()->hasRole('admin'))
                                <a href="{{route('stocks.edit',$product->id)}}">
                                    {{ $product->id }}
                                </a>
                            @else
                                {{ $product->id }}
                            @endif
                        </td>
                        <td>
                            @if(\Auth::user()->hasRole('admin'))
                                <a href="{{route('stocks.edit',$product->id)}}">
                                    {{ $product->title }}
                                </a>
                            @else
                                {{ $product->title }}
                            @endif
                        </td>

                        <td>{{ $product->categorySee->title}}</td>
                        <td>
                            @if($product->categorySee->id == $kofeinyi_apparat_category_id)
                            @else
                                {{ $product->count }}
                            @endif
                        </td>
                        <td>{{ $product->price}} грн.</td>

{{--
                        <td>@if($product->unlimited == 1) <i class="fa fa-plus-square"></i>@endif</td>
                        <td>
                            @if($product->published == 0)
                                <span class="red">Не доступно</span>
                            @endif
                            @if($product->published == 1)
                                <span class="green">Доступно</span>
                            @endif
                        </td>

--}}

                        @if(\Auth::user()->hasRole('admin'))
                            <td>
{{--


--}}
                                <a href="{{route('stocks.edit',$product->id)}}">
                                    <img src="/images/page-next.svg">
                                </a>

                            </td>
                        @endif
                    </tr>
                @endforeach
            </table>

        </div>
        @include('pagination.default', ['paginator' => $products])
    </div>
@endsection
