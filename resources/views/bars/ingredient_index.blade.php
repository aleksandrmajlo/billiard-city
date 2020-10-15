@extends('layouts.app')
@section('content')
    <div class="user">
        <div class="row">
            <div class="col-xs-12">
                <div class="user__title">
                    <h2>  @lang('ingrredient.title') </h2>
                </div>
                <div class="user_top over__formsearch_ing">
                    @if($ReadCount)
                        <add-ingr></add-ingr>
                    @endif
                    <search-ingr ></search-ingr>
                </div>
                <list-ingrs page="{{$page}}" law="{{$ReadCount}}"></list-ingrs>
                @include('pagination.default', ['paginator' => $ingredients])
            </div>
        </div>
    </div>
    <form-ingr></form-ingr>
@endsection
