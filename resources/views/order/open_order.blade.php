@extends('layouts.app')
@php App::setLocale(session('lng'));@endphp
@section('content')
    <section class="content-header">
        <h1>
            @lang('act.open_smena')
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div id="alert-warning" class="alert alert-warning hidden" role="alert">
                    @lang('act.warning')
                </div>
            </div>
            <div class="col-xs-12">
                <form method="POST"  id="openOrder" action="{{action('ChangeController@create')}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="user_id" value="{{$user_id}}">
                    @if($ingredients)
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="text-center">
                                    <a class="btn btn-primary btn-lg btnCat" data-toggle="collapse"
                                       href="#collapseIngrredient" role="button" aria-expanded="false"
                                       aria-controls="collapseExample">
                                        @lang('ingrredient.title')
                                    </a>
                                </div>
                                <div class="collapse" id="collapseIngrredient">
                                    <div class="card card-body">
                                        <table class="table">
                                            <tbody>
                                            @foreach($ingredients as $ingredient)
                                                <tr>
                                                    <td>
                                                        {{$ingredient->title}}
                                                        <input type="hidden" name="ingredients[]"
                                                               value="{{$ingredient->id}}">
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="number" required min="0"
                                                               step="0.01"
                                                               value="10"
                                                               name="count_ingredients[]" >
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($cats)
                        @foreach($cats as $cat)
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="text-center">
                                        <a class="btn btn-primary btn-lg btnCat" data-toggle="collapse"
                                           href="#collapseCat{{$cat->id}}" role="button" aria-expanded="false"
                                           aria-controls="collapseExample">
                                            {{$cat->title}}
                                        </a>
                                    </div>

                                    <div class="collapse" id="collapseCat{{$cat->id}}">
                                        <div class="card card-body">
                                            <table class="table">
                                                <tbody>
                                                @if($kofeinyi_apparat_category_id==$cat->id)
                                                    <tr>
                                                        <td>
                                                            @lang('act.kofeinyi_apparat')
                                                        </td>
                                                        <td>
                                                            <input class="form-control" type="number" required min="0"
                                                                   step="1"
                                                                   name="kofeinyi_apparat" >
                                                        </td>
                                                    </tr>
                                                @else
                                                    @foreach($cat->stocks as $stock)
                                                        @if(count($stock->ingredients)===0)
                                                            <tr>
                                                                <td>
                                                                    {{$stock->title}}
                                                                    <input type="hidden" name="stocks[]"
                                                                           value="{{$stock->id}}">
                                                                </td>
                                                                <td>
                                                                    <input class="form-control" type="number" required min="0"
                                                                           step="0.01"
                                                                           value="10"
                                                                           name="count_stocks[]" >
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @endif

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    @endif
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6 col-xs-6">
                                    <h5 class="box-title"> @lang('act.summa_start')</h5>
                                </div>
                                <div class="col-lg-2 col-xs-2">
                                    <input  value="0"
                                            type="number"
                                            name="summa_start"
                                            placeholder="@lang('act.summa_start')" class="form-control"
                                            required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <input type="submit" id="openOrderSubmit" class="btn btn-block btn-danger btn-lg "
                                   value="@lang('act.open_smena')">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection