@extends('layouts.app')
@section('content')
    <div class="user">
        <div class="row">
            <div class="col-xs-12">
                <div class="user__title">
                    <h2>@lang('site.users')</h2>
                </div>
                @if (session('status'))
                    <div class="alert alert-success">
                        @lang('site.add_user_form')
                    </div>
                @endif
                <div class="user_top">
                    <a class="edit modalShow" href="#" >@lang('site.add')</a>
                    <form method="get" id="searchform" >
                        <input value="{{ app('request')->input('q') }}"  name="q" type="text" >
                        @if(request()->has('q'))
                            <a style="color: black" href="{{$urlFilter}}">@lang('act.reset')</a>
                        @endif
                        <button type="submit">
                            <img src="/img/search.png" alt="search">
                        </button>
                    </form>
                </div>
                <div class="user_table">
                    <table>
                        <tr >
                            <td>@lang('site.imya')</td>
                            <td>Телефон</td>
                            <td>Роль</td>
                        </tr>
                        @foreach($users as $user)
                           <tr>
                               <td>
                                   <a href="/users/{{ $user->id }}">{{$user->name}}</a>
                               </td>
                               <td>{{$user->phone}}</td>
                               <td>
                                   @foreach ($user->roles as $role)
                                       {{$role->name}}
                                   @endforeach
                               </td>
                           </tr>
                        @endforeach
                    </table>
                </div>
                @include('pagination.default', ['paginator' => $users])
            </div>
        </div>
    </div>
    <div class="win" id="readOrderWin">
        <div class="form-user">
            <div class="row">
                <div class="col-xs-8 col-sm-6 col-sm-push-6">
                    <p class="p">
                        <span class="h4">@lang('site.add')</span>
                    </p>
                </div>
            </div>
            <form  method="post" >
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-xs-4 col-xs-xs-xs-6">
                        <label>@lang('site.imya')</label>
                    </div>
                    <div class="col-xs-8 col-xs-xs-xs-6">
                        <input name="name" required type="text" placeholder="@lang('site.name_plac')">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4 col-xs-xs-xs-6">
                        <label>@lang('site.phone')</label>
                    </div>
                    <div class="col-xs-8 col-xs-xs-xs-6">
                        <input type="hidden" name="phone">
                        <phone val=""></phone>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-4 col-xs-xs-xs-6">
                        <label>@lang('site.roles')</label>
                    </div>
                    <div class="col-xs-6 col-xs-xs-12 ">
                        <div class="col-xs-6 col-xs-xs-5 col-xs-xs-xs-6 no-pdd">
                            <div class="radio">
                                <input id="Admin" required type="radio" name="role_id" value="1">
                                <label class="label" for="Admin">Admin</label>
                                <input id="Manager" required type="radio" name="role_id" value="2">
                                <label class="label" for="Manager">Manager</label>
                                <input id="Barmen" required type="radio" name="role_id" value="3">
                                <label class="label" for="Barmen">Barmen</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4 col-xs-xs-xs-6">
                        <label>@lang('site.status')</label>
                    </div>
                    <div class="col-xs-6 col-xs-xs-12 ">
                        <div class="col-xs-6 col-xs-xs-5 col-xs-xs-xs-6 no-pdd">
                            <div class="radio">
                                <input id="status1" required type="radio" checked name="status" value="1">
                                <label class="label" for="status1">@lang('site.status_active')</label>

                                <input id="status0" required type="radio" name="status" value="0">
                                <label class="label" for="status0">@lang('site.status_not_active')</label>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 saves">
                        <input class="save" type="submit" value="@lang('site.save')">
                    </div>
                </div>
            </form>
        </div>
        <a class="close " id="closeWin" title="Закрыть " href="#close ">
        </a>
    </div>
@endsection
