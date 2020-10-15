@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            @lang('site.edit') товар
        </h1>
    </section>

    <section class="content">
        <div class="row">
            @if(Session::has('ProductUpdate'))
                    <div class="col-xs-12">
                        <div class="alert-info alert">
                            @lang('site.ProductUpdate')
                        </div>
                    </div>
            @endif
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">

                        <form method="POST" action="{{route('stocks.update',$stock->id)}}"
                              enctype="multipart/form-data">
                            {{ csrf_field() }}
                            {{method_field('PUT')}}

                            <div class="form-group">
                                <select name="categoryStock" class="form-control">
                                    @foreach($categoryStocks as $categoryStock)
                                        <option value="{{$categoryStock->id}}"
                                                @if($categoryStock->id == $stock->categorystock_id) selected @endif>
                                            {{$categoryStock->title}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>@lang('site.title')</label>
                                <input type="text" name="id" class="form-control"
                                       value="@if($stock){{ $stock->id}}@endif"
                                       style="display: none"/>
                                <input type="text" name="title" class="form-control"
                                       value="@if($stock){{ $stock->title}}@endif" required/>
                            </div>
                            <div class="form-group" id="formCount">
                                <label>
                                    @lang('site.na_skladi')
                                </label>
                                <input @if(!$ReadCount) readonly @endif type="number" min="0" step="0.1" name="count"
                                       class="form-control" value="@if($stock){{ $stock->count}}@endif"/>
                            </div>
                            <div class="form-group">
                                <label>@lang('site.cina')</label>
                                <input type="text" name="price" class="form-control"
                                       value="@if($stock){{ $stock->price}}@endif"/>
                            </div>

                            <div class="form-group">
                                <label>@lang('site.image')</label>
                                @if($stock&&$stock->image)
                                    <div>
                                        <img class="img-responsive" src="{{$stock->image}}">
                                    </div>
                                @endif
                                <input class="form-control" type="file" name="image">
                            </div>

                            <div class="form-group">
                                <label>Доступно</label>
                                <br>
                                <input name="published" type="radio" value="0" required
                                       @if(isset($stock->published) && $stock->published == 0) checked @endif>Нет
                                <input name="published" type="radio" value="1" required
                                       @if(isset($stock->published) && $stock->published == 1) checked @endif>Да
                            </div>
                            <hr/>
                            <div class="radio">
                                <label class="radio-inline">
                                    <input required value="1"
                                           @if(isset($stock->resolve) && $stock->resolve == 1) checked @endif
                                           type="radio" name="resolve" checked>@lang('site.resolveYes')</label>
                                <label class="radio-inline">
                                    <input required value="0"
                                           @if(isset($stock->resolve) && $stock->resolve == 0) checked @endif
                                           type="radio" name="resolve">@lang('site.resolveNot')</label>
                            </div>
                            <hr/>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="scales" name="unlimited" value="1"
                                           @if(isset($stock->unlimited) && $stock->unlimited == 1) checked @endif>
                                    Безліміт
                                </label>
                            </div>
                            <ingredient-addproduct lang="@lang('ingrredient.title')"
                                                   id="@if($stock){{$stock->id}}@endif"></ingredient-addproduct>
                            <div class="user_top ProductReadButtonBlock">
                                <input type='submit'
                                       value="@lang('site.Update')"
                                       class="btn btn-primary active"/>
                                <a href="#" class="dell DellProduct">
                                    @lang('site.del')
                                </a>
                            </div>

                        </form>
                        <form action="{{ route('stocks.destroy' , $stock->id ) }}" id="FormDeleteProduct" class="user_top"
                              method="POST">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
