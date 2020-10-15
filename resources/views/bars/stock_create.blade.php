@extends('layouts.app')
@section('content')
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
                        <form method="POST" action="{{route('stocks.store')}}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <select name="categoryStock" class="form-control">
                                    @foreach($categoryStocks as $categoryStock)
                                        <option value="{{$categoryStock->id}}">{{$categoryStock->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Назва</label>
                                <input type="text" name="id" class="form-control"
                                       value=""
                                       style="display: none"/>
                                <input type="text" name="title" class="form-control"
                                       value="" required/>
                            </div>
                            <div class="form-group" id="formCount">
                                <label>
                                    @lang('site.na_skladi')
                                </label>
                                <input @if(!$ReadCount) readonly @endif type="number" min="0" step="0.1" name="count"
                                       class="form-control" value=""/>
                            </div>
                            <div class="form-group">
                                <label>@lang('site.cina')</label>
                                <input type="text" name="price" class="form-control"
                                       value=""/>
                            </div>

                            <div class="form-group">
                                <label>@lang('site.image')</label>
                                <input class="form-control" type="file" name="image">
                            </div>

                            <div class="form-group">
                                <label>Доступно</label>
                                <br>
                                <input name="published" type="radio" value="0" required>Нет
                                <input name="published" type="radio" value="1" required>Да
                            </div>
                            <hr/>
                            <div class="radio">
                                <label class="radio-inline">
                                    <input required value="1"
                                           type="radio" name="resolve" checked>@lang('site.resolveYes')</label>
                                <label class="radio-inline">
                                    <input required value="0"
                                           type="radio" name="resolve">@lang('site.resolveNot')</label>
                            </div>
                            <hr/>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="scales" name="unlimited" value="1"
                                    >
                                    Безліміт
                                </label>
                            </div>
                            <ingredient-addproduct lang="@lang('ingrredient.title')" id="-1"></ingredient-addproduct>
                            <input type='submit'
                                   value=" @lang('site.add')"
                                   class="btn btn-primary active"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
