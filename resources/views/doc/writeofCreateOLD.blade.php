@extends('layouts.app')
@section('content')
    <div class="user">
        <div class="user__title">
            <h2>@lang('writeof.new')</h2>
        </div>
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
@endsection