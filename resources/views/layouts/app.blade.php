<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Billiards CRM</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/css/auto-complete.css">
    <link rel="stylesheet" href="/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/bower_components/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="/dist/css/AdminLTE.css">
    <link rel="stylesheet" href="/dist/css/skins/skin-blue.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>
    <link href="{{ asset('production/css/app.css') }}?version=1.0.1" rel="stylesheet">
    {{--
      cтили верстальщика
      /css/demo.css подключать отдельно  в контэнтэ
     --}}
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    {{-- стили верстальщика  --}}
    <script>
        var LanguneThisJs = '@php  echo session('lng');@endphp';
    </script>
</head>
<body class="hold-transition sidebar-mini @if($SidebarToggle) sidebar-collapse @endif ">
<a href="#x" class="overlay overlayDoc" id="win1"></a>
<div class="wrapper" id="app">
    <header class="header__main">
        <a href="/" class="logo decstor">
            <span class="logo-mini"><img src="/img/logo 1.png" alt="logo"></span>
            <span class="logo-lg"><img src="/img/logo 1.png" alt="logo"><span>BilliardCRM</span></span>
        </a>
        <nav class="navbar navbar-static-top">
            <a href="#" id="SidebarToggle" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <messages-header></messages-header>
                    <li class="calendar-menu hidden-xs">
                        <clock-header></clock-header>
                    </li>
                    <li class="time-menu hidden-xs"></li>
                    <li class="locale">
                        <a class="@if(session('lng') == 'ua') active @endif" href="/sessionLng?lng=ua">
                            <span class="time">UA</span>
                        </a>
                    </li>
                    <li class="locale">
                        <a class="@if(session('lng') == 'ru') active @endif" href="/sessionLng?lng=ru">
                            <span class="time">RU</span>
                        </a>
                    </li>
                    <li class="user user-menu">
                        <a href="#">
                            <span>{{  Auth::user()->name ?? " " }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           data-toggle="control-sidebar">@lang('site.exit')</a>
                    </li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </ul>
            </div>
        </nav>
    </header>
    <aside class="sidebar__main">
        <section class="sidebar">
            <ul class="sidebar-menu" data-widget="tree">
                @if(Auth::check())
                    @php
                        $user = Auth::user();
                        $user_id=$user->id;
                    @endphp
                    <li class="mob">
                        <a href="/" class="logo">
                        <span class="logo-lg">
                            <img src="/img/logo 1.png" alt="logo">BilliardCRM</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/customers">
                            <img src="/img/menu2.png" alt="База клиентов">
                            <span>@lang('site.customer_base')</span>
                        </a>
                    </li>
                    @if($user->hasRole('admin')||$user->hasRole('manager'))
                        <li class="active">
                            <a href="/booking">
                                <img src="/img/menu3.png" alt="Бронирования">
                                <span> @lang('site.reservations')</span>
                            </a>
                        </li>
                        <li class="active">
                            <a href="/table/open_table">
                                <img src="/img/menu4.png" alt="Бильярдная">
                                <span>@lang('site.open_table')</span>
                            </a>
                        </li>
                        <li class="active">
                            <a href="/open-bar">
                                <img src="/img/menu5.png" alt="Открытые заказы">
                                <span>@lang('site.open_order')</span>
                            </a>
                        </li>
                    @endif
                    @if($user->hasRole('barmen'))
                        <li class="active">
                            <a href="/open-bar">
                                <img src="/img/menu5.png" alt="Открытые заказы">
                                <span>@lang('site.open_order')</span>
                            </a>
                        </li>
                    @endif
                    @if($user->hasRole('admin'))
                        <li class="treeview">
                            <a href="">
                                <img src="/img/menu6.png" alt="Конфигурация">
                                <span>@lang('site.config')</span>
                                <span class="pull-right-container">
							    <i class="fa fa-angle-left pull-right"></i>
							</span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="/table">@lang('site.add_table')</a></li>
                                <li><a href="/tarif">@lang('site.add_tariff')</a></li>
                                <li><a href="/users"> @lang('site.add_user')</a></li>
                                <li><a href="/discount">@lang('site.discount')</a></li>
                                <li><a href="/cupon">@lang('site.coupons')</a></li>
                                <li><a href="/socket">@lang('site.Socket')</a></li>
                                <li><a href="/aprint">@lang('site.Print')</a></li>
                                <li><a href="/apitokens">@lang('site.TitleApi')</a></li>
                            </ul>
                        </li>
                    @endif
                    <li class="treeview">
                        <a href="">
                            <img src="/img/menu7.png" alt="Склад">
                            <span>@lang('site.sklad')</span>
                            <span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="/bars/ingredients"> <span>@lang('ingrredient.title')</span></a></li>
                            <li><a href="/bars/stocks"> <span>@lang('site.stock')</span></a></li>
                            <li><a href="/bars/categories"> <span>@lang('sidebar.categories')</span></a></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="">
                            <img src="/img/menu8.png" alt="Документы">
                            <span>@lang('act.title')</span>
                            <span class="pull-right-container">
							   <i class="fa fa-angle-left pull-right"></i>
							</span>
                        </a>
                        <ul class="treeview-menu">
                            @if( $user->hasRole('admin'))
                                <li>
                                    <a href="/doc/act">
                                        <span>@lang('act.acts')</span></a>
                                </li>
                            @endif
                            <li>
                                <a href="/doc/purchaseinvoice">
                                    <span>@lang('purchaseinvoice.title')</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{url('/doc/consumableinvoice')}}">
                                    <span>@lang('сonsumableinvoice.title')</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{url('/doc/writeof')}}">
                                    <span>@lang('writeof.title')</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="active">
                        <a href="{{route('history_orders')}}">
                            <img src="/img/menu9.png" alt="История заказов">
                            <span>@lang('site.stat')</span>
                        </a>
                    </li>

                    <li class="active">
                        <a href="/changes">
                            <img src="/img/menu10.png" alt="Смена">
                            <span>@lang('site.change')</span>
                        </a>
                    </li>

                      <li  class="treeview">
                        <a href="">
                            <img src="/img/menu11.png" alt="Аналитика">
                            <span>@lang('site.analytics')</span>
                            <span class="pull-right-container">
							   <i class="fa fa-angle-left pull-right"></i>
							</span>
                        </a>
                          <ul class="treeview-menu">
                              <li>
                                  <a href="/analytic/attendance">
                                      <span>@lang('site.analytic_attendance')</span>
                                  </a>
                              </li>                              
                              <li>
                                  <a href="{{ route('popularity-tables') }}">
                                      <span>@lang('analytic.popularity-tables')</span>
                                  </a>
                              </li>

                          </ul>
                    </li>

                @endif;
            </ul>
        </section>
    </aside>
    <div class=" wrapper__main">
        <section class="content__main">
            @yield('content')
        </section>
    </div>
</div>

    <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>

    <script src="{{ asset('production/js/app.js') }}?version=1.0.1" ></script>
   
    {{--    старые скрипты --}}
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('js/jquery.maskedinput.min.js') }}"></script>
    <script src="{{ asset('js/my.js') }}"></script>
    {{--    старые скрипты --}}


</body>
</html>
