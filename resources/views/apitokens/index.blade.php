@extends('layouts.app')
@section('content')
    <div class="user">
        <div class="row">
            <div class="col-xs-12">
                <div class="user__title">
                    <h2>@lang('site.TitleApi')</h2>
                </div>


                <div class="list-group" style="max-width: 400px;">
                    <a href="/helpapi" class="btn btn-danger" target="_blank"> @lang('site.LinkApiRu')</a>
                    <a href="/helpapi_en" class="btn btn-danger" target="_blank">@lang('site.LinkApiEn')</a>
                </div>

                <div>
                    <a class="btn btn-primary" href="{{route('apitokens.create')}}">@lang('site.CreateApi')</a>
                </div>
                <div class="user_table">
                    <table>
                        <tr>
                            <td>@lang('site.ServiceApi')</td>
                            <td>@lang('site.TokenApi')</td>
                            <td></td>
                        </tr>
                        @foreach($ApiTokens as $apitoken)
                            <tr>
                                <td>
                                    {{$apitoken->service}}
                                </td>
                                <td>{{$apitoken->otoken}}</td>
                                <td>
                                    <form method="POST" action="/apitokens/{{ $apitoken->id }}">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button class="btn btn-danger" type="submit"><i class="fa fa-remove"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                @include('pagination.default', ['paginator' => $ApiTokens])
            </div>
        </div>
    </div>
@endsection
