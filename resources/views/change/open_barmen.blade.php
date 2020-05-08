@extends('layouts.app')
@php
    App::setLocale(session('lng'));
@endphp
@section('content')
    <open-barmen></open-barmen>
@endsection