@extends('layouts.app')
@php App::setLocale(session('lng'));@endphp
@section('content')
    <section class="content-header">
        <h1>
            @lang('act.acts')
        </h1>
    </section>
    @include('doc.filter')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        @if($acts)
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
                                        @foreach($acts as $act)
                                            <tr>
                                                <td>{{$act->id}}</td>
                                                <td>{{$act->created_at}}</td>
                                                <td>{{$act->user->name}}</td>
                                                <td>
                                                    <a class="btn " href="{{url('/doc/act/'.$act->id)}}"> @lang('act.show_act')</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{ $acts->appends($_GET)->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection