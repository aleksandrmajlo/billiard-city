@extends('layouts.app')
@section('content')
    <div class="user">
        <div class="row">
            <div class="col-xs-12">
                <div class="user__title">
                    <h2>@lang('site.Categories') </h2>
                </div>
                <div class="user_top over__formsearch_ing">
                    @if($ReadCount)
                        <add-cat></add-cat>
                    @endif
                    <search-cat ></search-cat>
                </div>
                <list-cat page="{{$page}}" law="{{$ReadCount}}"></list-cat>
                @include('pagination.default', ['paginator' => $categories])
            </div>
        </div>
    </div>
    <form-cat></form-cat>
@endsection