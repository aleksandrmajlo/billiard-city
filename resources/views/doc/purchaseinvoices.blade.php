@extends('layouts.app')
@php App::setLocale(session('lng'));@endphp
@section('content')
    <div class="user">
        <div class="row">
            <div class="col-xs-6 col-xs-xs-12">
                <div class="user__title">
                    <h2>@lang('purchaseinvoice.title')</h2>
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            @lang('purchaseinvoice.created')
                        </div>
                    @endif
                    @include('doc.filter')
                </div>
            </div>
        </div>
        <div class="blue liken">
            <a href="#win1" class="overhead__buttom modalShow">
                <img src="/img/user-plus.png" alt="user-plus">
                <span> @lang('purchaseinvoice.new')</span>
            </a>
        </div>
        <div class="user_table acts__table">
            <table>
                <tr class="td-one">
                    <td>
                        ID
                    </td>
                    <td>
                        @lang('act.date')
                    </td>
                    <td>
                        @lang('act.worker')
                    </td>
                </tr>
                @foreach($purchaseinvoices as $act)
                    <tr>
                        <td>
                            <a href="{{url('/doc/purchaseinvoice/'.$act->id)}}">{{$act->id}}</a>
                        </td>
                        <td>{{$act->created_at}}</td>
                        <td>{{$act->user->name}}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        @include('pagination.default', ['paginator' => $purchaseinvoices])
    </div>
    @include('doc.purchaseinvoiceCreatePopup')
@endsection