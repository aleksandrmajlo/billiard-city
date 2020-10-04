@extends('layouts.app')
@section('content')
    <div class="user">
        <div class="row">
            <div class="col-xs-12 col-xs-xs-12">
                <div class="user__title">
                    <h2>{{$user->name}}</h2>
                </div>
            </div>
        </div>
        @if (session('status'))
            <div class="alert alert-success">
                @lang('site.update_user_form')
            </div>
        @endif
        @if (isset($errors) && $errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-8">
                <div class="form-user form-user-edit">
                    <form method="post" action="/users/{{ $user->id }}">
                        {{ method_field('PATCH') }}
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-xs-4 col-xs-xs-xs-6">
                                <label>@lang('site.imya')</label>
                            </div>
                            <div class="col-xs-8 col-xs-xs-xs-6">
                                <input value="{{$user->name}}" name="name" required type="text" placeholder="@lang('site.name_plac')">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-xs-xs-xs-6">
                                <label>@lang('site.phone')</label>
                            </div>
                            <div class="col-xs-8 col-xs-xs-xs-6">
                                <input type="hidden" name="phone" value="{{$user->phone}}">
                                <phone val="{{$user->phone}}"></phone>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-xs-xs-xs-6">
                                <label>@lang('site.roles')</label>
                            </div>
                            <div class="col-xs-6 col-xs-xs-12 ">
                                <div class="col-xs-6 col-xs-xs-5 col-xs-xs-xs-6 no-pdd">
                                    <div class="radio">
                                        <input id="Admin" required type="radio" @if($user->hasRole('admin')) checked @endif name="role_id" value="1">
                                        <label class="label" for="Admin">Admin</label>
                                        <input id="Manager" required type="radio" name="role_id" value="2" @if($user->hasRole('manager')) checked @endif>
                                        <label class="label" for="Manager">Manager</label>
                                        <input id="Barmen" required type="radio" name="role_id" value="3" @if($user->hasRole('barmen')) checked @endif>
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
                                        <input id="status1" required type="radio" @if($user->status==1) checked @endif name="status" value="1">
                                        <label class="label" for="status1">@lang('site.status_active')</label>
                                        <input id="status0" required type="radio" @if($user->status===0) checked @endif name="status" value="0">
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
            </div>
        </div>
    </div>
@endsection
