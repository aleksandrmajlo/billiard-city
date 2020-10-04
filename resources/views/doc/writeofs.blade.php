@extends('layouts.app')
@section('content')
    <div class="user">
        <div class="row mb-20">
            <div class="col-xs-6 col-xs-xs-12">
                <div class="user__title">
                    <h2>@lang('writeof.title')</h2>
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            @lang('writeof.created')
                        </div>
                    @endif
                    @include('doc.filter')
                </div>
            </div>
        </div>

        <div class="blue liken">
            <a href="#win1" class="overhead__buttom modalShow">
                <img src="/img/user-plus.png" alt="user-plus">
                <span> @lang('writeof.new')</span>
            </a>
           <!--
            <form method="get" name="searchform" id="searchform" action="">
                <input name="" type="text" placeholder=''>
                <button type="submit"><img src="/img/search.png" alt="search"></button>
            </form>
            -->
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
                            <a class="btn " href="{{url('/doc/writeof/'.$act->id)}}">{{$act->id}}</a>
                        </td>
                        <td>{{$act->created_at}}</td>
                        <td>{{$act->user->name}}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        @include('pagination.default', ['paginator' => $purchaseinvoices])
    </div>
    @include('doc.writeofCreatePopup')
@endsection