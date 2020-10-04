@extends('layouts.app')
@php App::setLocale(session('lng'));@endphp
@section('content')
    <div class="user">
        <div class="row">
            <div class="col-xs-6 col-xs-xs-12">
                <div class="user__title">
                    <h2>@lang('сonsumableinvoice.title')</h2>
                    @include('doc.filter')
                </div>
            </div>
        </div>
        {{--
        <div class="blue liken">
           <a href="#win1" class="liken__buttom">
               <img src="/img/liken.png" alt="colendar"><span>@lang('act.compareButton')</span>
           </a>
       <form method="get" name="searchform" id="searchform" action="">
           <input name="" type="text" placeholder=''>
           <button type="submit"><img src="img/search.png" alt="search"></button>
       </form>
        </div>
         --}}
        <div class="user_table acts__table">
            <table>
                <tr class="td-one">
                    <td>
                    </td>
                    <td>
                        @lang('сonsumableinvoice.date')
                    </td>
                    <td>
                        @lang('act.worker')
                    </td>
                    <td>
                        @lang('сonsumableinvoice.summa')
                    </td>
                    <td>
                        @lang('сonsumableinvoice.count')
                    </td>
                </tr>
                @foreach($consumableinvoices as $act)
                    <tr>
                        <td>
                            <a href="{{url('doc/consumableinvoice/'.$act->id)}}">{{$act->id}}</a>
                        </td>
                        <td>{{$act->created_at}}</td>
                        <td>
                            @if($act->user)
                                {{$act->user->name}}
                            @endif
                        </td>
                        <td>
                            {{$act->total}}
                        </td>
                        <td> {{$act->countth}}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        @include('pagination.default', ['paginator' => $consumableinvoices])
    </div>

    <!--
    <section class="content-header">
        <h1 class="mb-10">
            @lang('сonsumableinvoice.title')
            </h1>
        </section>
              @include('doc.filter')

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
@if($consumableinvoices)
        <div class="box-body">
            <div class="table-responsive">
                <table id="ActsTable" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <td>
                        </td>
                        <td>
@lang('сonsumableinvoice.date')
                </td>
                <td>
@lang('act.worker')
                </td>

                <td>
@lang('сonsumableinvoice.summa')
                </td>

                <td>
@lang('сonsumableinvoice.count')
                </td>
                <td></td>

            </tr>
            </thead>
            <tbody>
@foreach($consumableinvoices as $act)

            <tr>
                <td>{{$act->id}}</td>
                                                <td>{{$act->created_at}}</td>
                                                <td>
                                                    @if($act->user)
                {{$act->user->name}}
            @endif
                    </td>
                    <td>
{{$act->total}}
                    </td>
                    <td> {{$act->countth}}</td>
                                                <td>
                                                    <a class="btn " href="{{url('doc/consumableinvoice/'.$act->id)}}"> @lang('act.show_act')</a>
                                                </td>
                                            </tr>

                                        @endforeach
                </tbody>
            </table>
        </div>
{{ $consumableinvoices->appends($_GET)->links() }}
                </div>
@endif
            </div>
        </div>
    </div>
</div>
</section>
-->
@endsection