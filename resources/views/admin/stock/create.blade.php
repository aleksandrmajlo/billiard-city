@extends('layouts.app')

@section('content')
    @php
        App::setLocale(session('lng'));
    @endphp
    <section class="content-header">
        <h1>
            @lang('site.add') товар
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        @if(isset($stock->id))
                            <form method="POST" action="{{action('StockController@edit')}}" enctype="multipart/form-data" >
                        @else
                           <form method="POST" action="{{action('StockController@store')}}" enctype="multipart/form-data">
                        @endif
                                        {{ csrf_field() }}
                                        <input type="text" style="display: none;" name="page" class="form-control"
                                               value="{{ $getpage  ?? '' }}"/>
                                        <div class="form-group">
                                            <label>Категория</label>
                                            <input type="text" style="display: none;" name="cat" class="form-control"
                                                   value="{{ $cat  ?? '' }}"/>
                                        </div>
                                        @if(isset($stock->id))
                                            <div class="form-group">
                                                <select name="categoryStock" class="form-control">
                                                    @foreach($categoryStocks as $categoryStock)
                                                        <option value="{{$categoryStock->id}}"
                                                                @if($categoryStock->id == $stock->categorystock_id) selected @endif>{{$categoryStock->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                        @if(!isset($stock->id))
                                            <div class="form-group">
                                                <select name="categoryStock" class="form-control">
                                                    @foreach($categoryStocks as $categoryStock)
                                                        <option value="{{$categoryStock->id}}">{{$categoryStock->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <label>Назва</label>
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
                                            <input @if(!$ReadCount) readonly @endif type="number" min="0" step="0.1" name="count" class="form-control" value="@if($stock){{ $stock->count}}@endif"/>
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
                                                    <img class="img-responsive" src="{{$stock->image}}" >
                                                </div>
                                            @endif
                                            <input class="form-control"  type="file"    name="image">
                                        </div>

                                        <div class="form-group">
                                            <label>Доступно</label>
                                            <br>
                                            <input name="published" type="radio" value="0" required
                                                   @if(isset($stock->published) && $stock->published == 0) checked @endif>Нет
                                            <input name="published" type="radio" value="1" required
                                                   @if(isset($stock->published) && $stock->published == 1) checked @endif>Да
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="scales" name="unlimited" value="1"
                                                       @if(isset($stock->unlimited) && $stock->unlimited == 1) checked @endif>
                                                Безліміт
                                            </label>
                                        </div>
                                        <ingredient-addproduct lang="@lang('ingrredient.title')" id="@if($stock){{$stock->id}}@else{{-1}}@endif"></ingredient-addproduct>
                                        <input type='submit'
                                               value="@if(isset($stock->id)) @lang('site.edit') @else @lang('site.add') @endif"
                                               class="btn btn-primary active"/>
                                    </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
