@extends('layouts.app')
@php App::setLocale(session('lng'));@endphp
@section('content')

    <section class="content-header">
        <h1 class="mb-10">
            @lang('purchaseinvoice.title') #{{$purchaseinvoice->id}}  {{$purchaseinvoice->created_at}}
        </h1>
    </section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 ">
                <div class="box">
                    <div class="box-body">
                        <dl class="row">
                            <dt class="col-sm-6"> @lang('purchaseinvoice.title')</dt>
                            <dd class="col-sm-6">
                                #{{$purchaseinvoice->id}} {{$purchaseinvoice->created_at}}
                            </dd>

                            <dt class="col-sm-6">@lang('act.worker')</dt>
                            <dd class="col-sm-6">
                                {{$purchaseinvoice->user->name}}
                            </dd>

                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 ">
                <div class="box">

                    <div class="box-body">
                        <table id="ActsTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
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
                                    @lang('purchaseinvoice.sklad')
                                </td>
                            </tr>
                            </thead>
                            <tbody>
                               @foreach($purchaseinvoice->ingredients as $ingredient)
                                   @include('doc.ingredient')
                               @endforeach
                               @foreach($purchaseinvoice->stocks as $stock)
                                  @include('doc.stock')
                               @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection