@extends('layouts.app')
@section('content')
    <div class="user">
        <div class="row">
            <div class="col-xs-6 col-xs-xs-12">
                <div class="user__title">
                    <h2>@lang('act.acts')</h2>
                    @include('doc.filter')
                </div>
            </div>
        </div>
        <div class="blue liken">
            <a href="#win1" class="liken__buttom">
                <img src="/img/liken.png" alt="colendar"><span>@lang('act.compareButton')</span>
            </a>
        </div>
        <div class="user_table acts__table">
            <table>
                <tr class="td-one">
                    <td>id</td>
                    <td>@lang('act.date')</td>
                    <td>@lang('act.worker')</td>
                </tr>
                @foreach($acts as $act)
                    <tr>
                        <td>
                            <a href="{{url('/doc/act/'.$act->id)}}">{{$act->id}}</a>
                        </td>
                        <td>{{$act->created_at}}</td>
                        <td>
                            @if($act->user)
                                {{$act->user->name}}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        @include('pagination.default', ['paginator' => $acts])
    </div>
    @include('doc.compare_act_popup')
@endsection