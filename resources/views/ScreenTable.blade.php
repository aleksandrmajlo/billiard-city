<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @php
        App::setLocale(session('lng'));
    @endphp

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Billiards CRM</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap&subset=cyrillic" rel="stylesheet">
    <link href="{{ asset('production/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">

    <script>
        var LanguneThisJs = '@php  echo session('lng');@endphp';
    </script>

    <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script src="{{ asset('production/js/app.js') }}" defer></script>

    <style type="text/css">
        html{
            zoom: 1.5;
        }
    </style>

</head>
<body>
<div class="wrapper billiard" id="app">
    <div class="row">
        <div class="col-md-12">
            <screen-table></screen-table>
        </div>
    </div>
</div>
</body>
</html>