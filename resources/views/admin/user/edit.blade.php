@extends('layouts.app')

@section('content')
    @php
        App::setLocale(session('lng'));
    @endphp
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
         <form method="POST" action="{{action('UserController@edit')}}" enctype="multipart/form-data" />

           {{ csrf_field() }}
              <input id="name" type="text" style="display: none" class="form-control" name="id" value="{{ $_GET['id'] }}" required autofocus>

             <label for="name"  >Name</label>
            <input id="name" type="text" class="form-control" name="name" value="{{ $users->name ?? '' }}" required autofocus>

              <label for="name"  >E-mail</label>
              <input id="name" type="email" class="form-control" name="email" value="{{ $users->email ?? '' }}" required autofocus>


              <label for="name"  >Password</label>
              <input id="password" type="password" class="form-control" name="password" >

              <label for="name"  >Repeat password</label>
              <input id="password-confirm" type="password" class="form-control" name="password_confirmation" >


                  <label for="password-confirm"  >Avatars</label>

                      <input id="avatar" type="file" class="form-control" name="avatar" >



                  <label for="password-confirm" >Language</label>


                      <select name="language" class="form-control">
                          <option value="ua">Українська</option>
                          <option value="ru">Русский</option>
                          <option value="en">English</option>
                      </select>


                <label>Роль</label><br>
                <select  class="form-control"  name="roles">
                    <option value="-">-</option>
              <option value="1">Admin</option>
              <option value="3">Barmen</option>
              <option value="2">Manager</option>
            </select>
            <br>
           <input type = 'submit' value = "@lang('site.edit')"  class="btn btn-primary active" />
           </form>
 </div>
</div>
</div>
</div>
</section>
@endsection
