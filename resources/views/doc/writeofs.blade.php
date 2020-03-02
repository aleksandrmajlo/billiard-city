@extends('layouts.app')
@php App::setLocale(session('lng'));@endphp
@section('content')
    <section class="content-header">
        <h1 class="mb-10">
            @lang('writeof.title')
        </h1>
    </section>
    @if (\Session::has('success'))
        <div class="alert alert-success">
            @lang('writeof.created')
        </div>
    @endif
    @include('doc.filter')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <a href="{{url('doc/writeof/create')}}" class="btn btn-primary">
                            @lang('writeof.new')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        @if($purchaseinvoices)
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="ActsTable" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <td>
                                            </td>
                                            <td>
                                                @lang('act.date')
                                            </td>
                                            <td>
                                                @lang('act.worker')
                                            </td>
                                            <td>
                                                @lang('act.detali')
                                            </td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($purchaseinvoices as $act)
                                            <tr>
                                                <td>{{$act->id}}</td>
                                                <td>{{$act->created_at}}</td>
                                                <td>{{$act->user->name}}</td>
                                                <td>
                                                    <a class="btn " href="{{url('/doc/writeof/'.$act->id)}}"> @lang('act.show_act')</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{ $purchaseinvoices->appends($_GET)->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection