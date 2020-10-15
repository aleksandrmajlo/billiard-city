@extends('layouts.app')
@section('content')
    <div class="user">
        <div class="row">
            <div class="col-xs-12">
                <div class="user__title">
                    <h2> @lang('site.customer_base')</h2>
                </div>
                <div class="user_top">
                    <a class="edit modalShow" href="#">@lang('site.add')</a>
                    <search-customers></search-customers>
                </div>
                <list-customers page="{{$page}}"></list-customers>
                @include('pagination.default', ['paginator' => $customers])
            </div>
        </div>
    </div>
    <div class="win" id="readOrderWin">
        <div class="form-user">
            <div class="row">
                <div class="col-xs-8 col-sm-6 col-sm-push-6">
                    <p class="p"><span class="h4">@lang('site.add_customers')</span></p>
                </div>
                <div class="col-xs-4 col-sm-6 col-sm-pull-6">
                </div>
            </div>
            <add-customer></add-customer>
        </div>
        <a class="close " id="closeCompareActWin" title="Закрыть " href="#close "></a>
    </div>
@endsection
