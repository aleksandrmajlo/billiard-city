@extends('layouts.app')
@section('content')
    <div class="user statPageUser">
        <div class="user__title">
            <h2> @lang('site.change')</h2>
            <div class="filter-block filter-order">
                <h4>@lang('orders.filter')</h4>
                <form method="get" action="{{action('ChangeController@index')}}" class="filter-form">
                    <div class="row">
                        @if($isAdmin)
                            <div class="col-md-7">
                                <div class="overflowHidden">
                                    <div class="col-xs-4 col-xs-xs-xs-12 left-pad">
                                        <label>@lang('site.rabotnik')</label>
                                    </div>
                                    <div class="col-xs-8 col-xs-xs-xs-12">
                                        <select name="user_id">
                                            <option value="0">@lang('orders.all')</option>
                                            @foreach($workers as $worker)
                                                <option @if($req_user==$worker->id) selected
                                                        @endif value="{{ $worker->id }}">{{  $worker->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-5">
                            <div class="col-xs-4 col-xs-xs-xs-12 left-pad">
                                <label>@lang('orders.date_from_to')</label>
                            </div>
                            <div class="col-xs-8 col-xs-xs-xs-12 right-pad">
                                <input class="order-time" value="{{request()->get('start')}}" name="start"
                                       type="date" placeholder="20.12.2019">
                                <input class="order-time" value="{{request()->get('stop')}}" name="stop"
                                       type="date" placeholder="20.01.2020">
                            </div>
                            <div class="col-xs-12">
                                <div class="buttons">
                                    <a href="/changes/" type="reset">@lang('act.reset')</a>
                                    <button type="submit">@lang('act.send')</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="user_table acts__table">
            <table>
                <tr class="td-one">
                    <td>id</td>
                    <td>@lang('site.polzovatel')</td>
                    <td>@lang('site.date_start')</td>
                    <td>@lang('site.startzmini')</td>
                    <td>@lang('site.clientiv')</td>
                    <td>@lang('site.nal')</td>
                    <td>@lang('site.cart')</td>
                    <td>@lang('site.zarabotano')</td>
                    <td>@lang('site.sumstartz')</td>
                    <td>@lang('site.sumstendz')</td>
                </tr>
                @foreach($changes as $change)
                    <tr>
                        <td><a href="/changes/{{ $change->id }}">{{ $change->id }}</a></td>
                        <td>{{ $change->user->name ?? ''}}</td>
                        <td>{{ $change->start }}</td>
                        <td>
                            @if(empty($change->stop))
                                <span class="green">@lang('site.changeGo')</span>
                            @endif {{ $change->stop }}
                        </td>
                        <td> {{count($change->orders) }}</td>
                        <td>  {{ $change->nal }} ₴</td>
                        <td>  {{ $change->cart }} ₴</td>
                        <td> {{ $change->total }} ₴</td>
                        <td>{{ $change->summa_start }} ₴</td>
                        <td>
                            @if(!empty($change->stop))
                                {{ $change->summa_end??0  }} ₴
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        @include('pagination.default', ['paginator' => $changes])

    </div>
@endsection

