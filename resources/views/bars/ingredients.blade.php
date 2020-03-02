@extends('layouts.app')
@php App::setLocale(session('lng'));@endphp
@section('content')
    <section class="content-header">
        <h1>
           @lang('ingrredient.title')
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    @if(\Auth::user()->hasRole('admin'))
                        <div class="box-header">

                            <div class="row">
                                <div class="col-md-6 text-center mb-10">
                                    <a data-toggle="collapse" href="#collapseExample" role="button" class="btn btn-lg btn-success  " >+ @lang('site.add')  @lang('ingrredient.add')</a>
                                </div>
                                <div class="col-md-6 text-center">
                                    <a href="{{route('stockcreate')}}" type="button" class="btn btn-lg btn-success  " style="width: 200px;">+ @lang('site.add') продукт</a>
                                </div>
                            </div>

                             <div class="collapse" id="collapseExample">
                                <div class="card card-body">
                                    <form method="POST"   id="addIngredientForm">
                                       {{ csrf_field() }}
                                        <div class="form-group">
                                           <label>@lang('ingrredient.name')<span class="text-danger">*</span></label>
                                           <input type="text" class="form-control" name="title" required>
                                       </div>
                                        <div class="form-group">
                                           <label>@lang('site.na_skladi')</label>
                                           <input type="number" class="form-control" name="count" min="0" step="0.01" required>
                                       </div>
                                        <input type = 'submit' id="addIngredienrButton" value = "@lang('site.add')"  class="btn btn-primary active" />
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="box-body">

                        <div class="table-responsive">
                            <table id="IngredientTable" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <td>@lang('ingrredient.name')</td>
                                    <td>@lang('site.na_skladi')</td>
                                    <td style="max-width: 300px;"></td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($ingredients as $ingredient)
                                    <tr>
                                        <td>
                                            {{$ingredient->title}}
                                        </td>
                                        <td>
                                            @if($ReadCount)
                                                <div class="form-group">
                                                    <input class="form-control" type="number" name="count" value="{{$ingredient->count}}">
                                                </div>
                                            @else
                                                {{$ingredient->count}}
                                            @endif
                                        </td>
                                        <td>
                                            <div style="display: flex;aling-items:middle;justify-content:center;">
                                                @if($ReadCount)
                                                    <ingredient-button lang="@lang('ingrredient.save')" id="{{$ingredient->id}}"></ingredient-button>
                                                    <form action="{{ url('bars/ingredient' , $ingredient->id ) }}" method="POST">
                                                        {{ csrf_field() }}
                                                        {{ method_field('delete') }}
                                                        <button style="margin-left: 10px;" class="btn  btn-primary  btn-danger"
                                                                type="submit">@lang('site.del')</button>
                                                    </form>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-12" style="margin-top:20px;">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                    <tr>
                                        <td>@lang('act.kofeinyi_apparat')</td>
                                        <td>{{$kofeinyiapparatcount}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection