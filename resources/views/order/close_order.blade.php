@extends('layouts.app')
@php App::setLocale(session('lng'));@endphp
@section('content')
    <section class="content-header">
        <h1>
            @lang('act.close_smena')
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
                <div id="alert-error" class="alert alert-error hidden" role="alert">
                    <h2>@lang('act.error')</h2>
                    <div class="messageError">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <td>@lang('act.name')</td>
                                <td>@lang('act.oldCount')</td>
                                <td>@lang('act.thisCount')</td>
                            </tr>
                            </thead>
                            <tbody id="tbodyCloseOrder">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 hidden" id="order_OrderForced_Conteer">
                <form id="closeFormOrderForced">
                     <div class="form-group text-center">
                         <button type="submit" id="closeFormOrderButton"  class="btn btn-danger btn-lg">@lang('act.order_OrderForced')</button>
                     </div>

                </form>
            </div>
            <div class="col-xs-12">
                <form id="closeFormOrder">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{$id}}">
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
                                                               name="count_ingredients[]">
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
                                                                   name="kofeinyi_apparat">
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
                                                                    <input class="form-control" type="number" required
                                                                           min="0"
                                                                            step="0.01"
                                                                           value="10"
                                                                           name="count_stocks[]">
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
                                    <h5 class="box-title"> @lang('act.summa_end')</h5>
                                </div>
                                <div class="col-lg-2 col-xs-2">
                                    <input style="" value="0" type="number" name="summa_end"
                                           placeholder="@lang('site.summa')" class="form-control"
                                           required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <input type="submit" id="closeOrderSubmit" class="btn btn-block btn-danger btn-lg "
                                   value="@lang('act.close_smena')">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection