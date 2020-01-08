@extends('layouts.app')
@php
  App::setLocale(session('lng'));
@endphp
@section('content')
  <section class="content-header">
    <h1>
      @lang('site.users')
    </h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
                <a href="/register" type="button" class="btn btn-lg btn-success" style="width: 200px;">+ @lang('site.add')</a>
            </div>
          <div class="box-body">
            <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>@lang('site.imya')</th>
                    <th>E-mail</th>
                    <th>Роль</th>
                    <th width="120px"> </th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($users as $user)
                    <tr>
                      <td>{{ $user->name }}</td>
                      <td>{{ $user->email }}</td>
                      <td>@foreach ($user->roles as $role)
                          {{$role->name}}
                        @endforeach</td>
                      <td>
                        <a href="/user-edit?id={{ $user->id }}" class="btn btn-block btn-primary btn-sm">@lang('site.edit')</a>
                        <form action="{{ url('/user' , $user->id ) }}" method="POST">
                          {{ csrf_field() }}
                          {{ method_field('delete') }}
                           <button style="margin-top: 7px;" class="btn btn-block btn-primary btn-sm btn-danger" type="submit">@lang('site.del')</button>
                        </form>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
            </div>
            {{ $users->links() }}
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection
