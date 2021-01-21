@extends('layouts.app')
@section('content')
    <div class="user">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="analytics__title">
                    <h2>@lang('site.analytics')<span>@lang('analytic.popularity-tables')</span></h2>
                </div>
                <popularitytables-analitic></popularitytables-analitic>
            </div>
            <popularitytablesfooter-analitic></popularitytablesfooter-analitic>
        </div>
    </div>
    <script>
        var tables = {!! json_encode($tables->toArray()) !!};
    </script>
@endsection
