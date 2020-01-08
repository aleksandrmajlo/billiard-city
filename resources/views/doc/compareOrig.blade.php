@extends('layouts.app')
@php App::setLocale(session('lng'));@endphp
@section('content')

<section class="content-header">
    <h1>
        @lang('act.compare')
    </h1>
</section>


<section class="filterConteer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        @if($act1&&$act2)
                        <div class="flexBlock">
                            <div class="itemAct">
                                <p>Акт #{{$act1->id}} @lang('act.from') {{$act1->created_at}} </p>
                                <p>@lang('act.worker'): {{$act1->user->name}} </p>
                                <p>@lang('act.smena'): {{$act1->change->id}} </p>
                            </div>
                            <div class="itemAct">
                                <p>Акт #{{$act2->id}} @lang('act.from') {{$act2->created_at}} </p>
                                <p>@lang('act.worker'): {{$act2->user->name}} </p>
                                <p>@lang('act.smena'): {{$act2->change->id}} </p>
                            </div>
                        </div>
                        @endif
                        <form method="get">
                            <div class="flexBlock flexCompare">
                                <div class="itemFilter itemSelect">
                                    <div class="form-group">
                                        <label>@lang('act.act') 1</label>
                                        <select required class="form-control" name="act1">
                                            @foreach($acts as $act)
                                            <option
                                                @if(request()->has('act1')&&request()->act1==$act->id)
                                                selected
                                                @endif
                                                value="{{$act->id}}">
                                                #{{$act->id}} {{$act->created_at}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="itemFilter itemSelect ">
                                    <div class="form-group">
                                        <label>@lang('act.act') 2 </label>
                                        <select required class="form-control" name="act2">
                                            @foreach($acts as $act)
                                            <option
                                                @if(request()->has('act1')&&request()->act2==$act->id)
                                                selected
                                                @endif
                                                value="{{$act->id}}">
                                                #{{$act->id}} {{$act->created_at}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="itemFilter">
                                    <button type="submit" class="btn btn-primary">@lang('act.compareButton')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if($act1&&$act2)
<section class="filterConteer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        @include('doc.filter_act_compare')
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@if($act1&&$act2)
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 mt-10">
                <div class="box">
                    <table id="ActsTable" class="table table-bordered table-striped">
                        <thead>
                        <td>
                            @lang('act.name')
                        </td>
                        <td>
                            @lang('act.cat')
                        </td>

                        <td>
                            @lang('act.type')
                        </td>

                        <td>
                            @lang('act.sklad_smena') {{$act1->change->id}}
                        </td>
                        <td>
                            @lang('act.sklad_smena') {{$act2->change->id}}
                        </td>

                        </thead>
                        <tbody>

                        @if(request()->has('type'))
                        @if(request()->type=='product')
                        @foreach($comp_results['stocks'] as $stock)
                        @include('doc.compare_stock')
                        @endforeach
                        @endif
                        @if(request()->type=='ingredient')
                        @foreach($comp_results['ingredients'] as $ingredient)
                        @include('doc.compare_ingredient')
                        @endforeach
                        @endif
                        @else
                        @foreach($comp_results['ingredients'] as $ingredient)
                        @include('doc.compare_ingredient')
                        @endforeach
                        @foreach($comp_results['stocks'] as $stock)
                        @include('doc.compare_stock')
                        @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endif



@endsection