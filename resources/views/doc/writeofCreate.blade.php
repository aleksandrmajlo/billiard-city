@extends('layouts.app')
@php App::setLocale(session('lng'));@endphp
@section('content')

    <section class="content-header">
        <h1 class="mb-10">
            @lang('writeof.new')
        </h1>
    </section>

    @if (\Session::has('error'))
        <div class="alert alert-success">
        </div>
    @endif

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <form method="POST" action="{{action('Docs\WriteofController@store')}}"
                                  enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <writeof-products></writeof-products>
                                <input type='submit'
                                       value="@lang('writeof.save')"
                                       class="btn btn-primary btn-lg active"/>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection