@extends('layouts.app')
@php App::setLocale(session('lng'));@endphp
@section('content')

    <section class="content-header">
        <h1 class="mb-10">
            @lang('сonsumableinvoice.title')
        </h1>
    </section>
    @include('doc.filter')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        @if($consumableinvoices)
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="ActsTable" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <td>
                                            </td>
                                            <td>
                                                @lang('сonsumableinvoice.date')
                                            </td>
                                            <td>
                                                @lang('act.worker')
                                            </td>
    
                                            <td>
                                                @lang('сonsumableinvoice.summa')
                                            </td>
    
                                            <td>
                                                @lang('сonsumableinvoice.count')
                                            </td>
                                            <td></td>
    
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($consumableinvoices as $act)

                                            <tr>
                                                <td>{{$act->id}}</td>
                                                <td>{{$act->created_at}}</td>
                                                <td>
                                                    @if($act->user)
                                                        {{$act->user->name}}
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$act->total}}
                                                </td>
                                                <td> {{$act->countth}}</td>
                                                <td>
                                                    <a class="btn " href="{{url('doc/consumableinvoice/'.$act->id)}}"> @lang('act.show_act')</a>
                                                </td>
                                            </tr>

                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{ $consumableinvoices->appends($_GET)->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection