@extends('layouts.app')

@section('content')
  @php
    App::setLocale(session('lng'));
  @endphp
  <section class="content-header">
    <h1>
      @lang('site.add')
    </h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
         <form method="POST" action="{{action('TableController@store')}}" enctype="multipart/form-data" />
           {{ csrf_field() }}
           <label>@lang('site.title')
           </label>
           <input type="text" name="title"  class="form-control"  required />
            <br>
           <input type = 'submit' value = "@lang('site.add')"  class="btn btn-lg btn-primary active" />
           </form>
 </div>
</div>
</div>
</div>
</section>
@endsection
