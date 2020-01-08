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
    {{--    <link href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">--}}
    <link rel="stylesheet" href="/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/bower_components/Ionicons/css/ionicons.min.css">
    {{--<link rel="stylesheet" href="/dist/css/AdminLTE.min.css">--}}
    <link rel="stylesheet" href="/dist/css/AdminLTE.css">
    <link rel="stylesheet" href="/dist/css/skins/skin-blue.min.css">
    <link rel="stylesheet"          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link href="{{ asset('/css/datepicker.min.css') }}" rel="stylesheet">

    <link href="{{ asset('/css/my.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>

    <link href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
    
    <link href="{{ asset('/css/order.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style type="text/css">
        .example-modal .modal {
            position: relative;
            top: auto;
            bottom: auto;
            right: auto;
            left: auto;
            display: block;
            z-index: 1;
        }
        .example-modal .modal {
            background: transparent !important;
        }
    </style>
    <script>
      var LanguneThisJs='@php  echo session('lng');@endphp';
    </script>

    <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>

    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('js/jquery.maskedinput.min.js') }}"></script>
    <script src="{{ asset('js/datepicker.min.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
    <script src="/js/auto-complete.js"></script>
    <script src='/js/uk.js'></script>
    <script src="{{ asset('js/my.js') }}"></script>

</head>
<body class="hold-transition skin-blue sidebar-mini">
@php
    App::setLocale(session('lng'));
@endphp
@if(request()->has('cat'))
    <style>
        tr.catstock{
            display: none !important;
        }
        tr.cat_{{request()->cat}}{
            display: table-row !important;
        }
    </style>
@endif
<div id="app">
    <div class="wrapper notPopover">
        <header class="main-header">
            <a href="/" class="logo">
                <span class="logo-mini"><b>B</b>S</span>
                <span class="logo-lg">
                    <img src="/images/logo.png" style="width: 43px; position:relative; left: -5px; top: -4px;"><b>Billiards</b>CRM
                </span>
            </a>
            <nav class="navbar navbar-static-top" role="navigation">
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu @if(session('lng') == 'ua') active @endif"><a
                                    href="/sessionLng?lng=ua">ua</a></li>
                        <li class="dropdown user user-menu @if(session('lng') == 'ru') active @endif"><a
                                    href="/sessionLng?lng=ru">ru</a></li>
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="https://crm.billiard-city.com//{{ Auth::user()->avatar }}" class="user-image"
                                     alt="User Image">
                                <span class="hidden-xs" id="loginUser">{{  Auth::user()->name ?? " " }}</span>
                            </a>
                        </li>
                        <li class=" ">
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                @lang('site.exit')
                            </a></li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>

                    </ul>
                </div>
            </nav>
        </header>
        <aside class="main-sidebar">
            <section class="sidebar">
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="active"><a href="/"><i class="fa fa-link"></i> <span>@lang('site.panel')</span></a></li>
                    <li><a href="/customers"><i class="fa fa-users"></i> <span>@lang('site.customer_base')</span></a></li>
                    @if(Auth::check() && \Auth::user()->hasRole('admin'))
                        <li><a href="/booking"><i class="fa fa-hourglass-2"></i> <span>@lang('site.reservations')</span></a>
                        </li>
                    @endif
                    @if(Auth::check() && \Auth::user()->hasRole('manager'))
                        <li><a href="/booking"><i class="fa fa-hourglass-2"></i> <span>@lang('site.reservations')</span></a></li>
                    @endif
                    @php
                              $users = Auth::user()->id;
                              $openChange = \App\Change::where('stop', '=', null)
                                               ->where('user_id', '=', $users)
                                               ->orderBy('created_at', 'desc')
                                               ->get();
                    @endphp
                    @if(Auth::check() && \Auth::user()->hasRole('manager'))
                        @if (count($openChange) > 0)
                            <li><a href="/open-table"><i class="fa fa-history"></i>
                                    <span>@lang('site.open_table')</span></a></li>
                            <li><a href="/open-bar"><i class="fa fa-history"></i> <span>@lang('site.open_order')</span></a>
                            </li>
                        @endif
                    @endif
                    @if(Auth::check() && \Auth::user()->hasRole('barmen'))
                        @if (count($openChange) > 0)
                            <li>
                                <a href="/open-bar"><i class="fa fa-history"></i> <span>@lang('site.open_order')</span></a>
                            </li>
                        @endif
                    @endif
                    @if(Auth::check() && \Auth::user()->hasRole('admin'))
                        <li>
                            <a href="/open-table"><i class="fa fa-history"></i>
                                <span>@lang('site.open_table')</span></a>
                        </li>
                        <li><a href="/open-bar"><i class="fa fa-history"></i> <span>@lang('site.open_order')</span></a>
                        </li>
                    @endif

                    @if(Auth::check() && \Auth::user()->hasRole('admin'))
                        <li class="treeview">
                            <a style="cursor: pointer"><i class="fa fa-gears "></i> <span>@lang('site.config')</span>
                                <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                          </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="/table">@lang('site.add_table')</a></li>
                                <li><a href="/tarif">@lang('site.add_tariff')</a></li>
                                <li><a href="/user"> @lang('site.add_user')</a></li>
                                <li><a href="/discount">@lang('site.discount')</a></li>
                                <li><a href="/cupon">@lang('site.coupons')</a></li>
                                <li><a href="/socket">@lang('site.Socket')</a></li>
                                <li><a href="/aprint">@lang('site.Print')</a></li>
                            </ul>
                        </li>

                    @endif
                    <li class="treeview">
                        <a style="cursor: pointer"><i class="fa fa-archive"></i>
                            <span>@lang('site.sklad')</span>
                            <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                      </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="/bars/ingredient"> <span>@lang('ingrredient.title')</span></a></li>
                            <li><a href="/stock"> <span>@lang('site.stock')</span></a></li>
                            @if(Auth::check() && \Auth::user()->hasRole('admin'))
                                <li><a href="/category"> <span>@lang('sidebar.categories')</span></a></li>
                            @endif
                        </ul>
                    </li>

                    {{-- Акты start--}}

                    <li class="treeview">
                        <a style="cursor: pointer">
                            <i class="fa fa-archive"></i>
                            <span>@lang('act.title')</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            @if(Auth::check() && \Auth::user()->hasRole('admin'))
                            <li>
                                <a href="/doc/act">
                                    <span>@lang('act.acts')</span></a>
                            </li>
                            <li>
                                <a href="/doc/compare">
                                    <span>@lang('act.actSr')</span></a>
                            </li>
                            @endif
                            <li>
                               <a href="/doc/purchaseinvoice">
                                   <span>@lang('purchaseinvoice.title')</span>
                               </a>
                            </li>
                            @if(Auth::user()->hasRole('admin'))
                               <li>
                                  <a href="{{url('/doc/consumableinvoice')}}">
                                      <span>@lang('сonsumableinvoice.title')</span>
                                  </a>
                               </li>
                            @endif
                        </ul>
                    </li>

                    {{-- Акты end--}}

                    </li>
                    <li><a href="/stat"><i class="fa fa-area-chart"></i> <span>@lang('site.stat')</span></a></li>
                    <li><a href="/change"><i class="fa fa-briefcase"></i> <span>@lang('site.change')</span></a></li>
                </ul>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>© 2019 Bethoven Digital</b>
            </div>
            <strong>    @lang('site.coperaight') 067 544 01 52, Telegram @alex_ruks
        </footer>
        <div class="control-sidebar-bg"></div>
    </div>
</div>

<script>
    //Date picker
    $('#datepicker').datepicker({
        autoclose: true
    });
    $(document).ready(function () {
        $('#calendar').fullCalendar({
            firstDay: 1,
            height: 200,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'οюнь', 'οюль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            monthNamesShort: ['Янв.', 'Фев.', 'Март', 'Апр.', 'Май', 'οюнь', 'οюль', 'Авг.', 'Сент.', 'Окт.', 'Ноя.', 'Дек.'],
            dayNames: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"],
            dayNamesShort: ["ВС", "ПН", "ВТ", "СР", "ЧТ", "ПТ", "СБ"],
            buttonText: {
                prev: "&nbsp;&#9668;&nbsp;",
                next: "&nbsp;&#9658;&nbsp;",
                prevYear: "&nbsp;&lt;&lt;&nbsp;",
                nextYear: "&nbsp;&gt;&gt;&nbsp;",
                today: "Сегодня",
                month: "Месяц",
                week: "Неделя",
                day: "День"
            }
        });
        $(document).ready(function () {
            $('.js-example-basic-single').select2({
                tags: true
            });
            $('.js-example-basic-single2').select2({
                placeholder: 'Добавить товар'
            });
        });
    });
</script>
<script>
    $(document).ready(function () {
        if ($('#advanced-demo').length) {
            var demo2 = new autoComplete({
                selector: '#advanced-demo',
                minChars: 2,
                source: function (term, suggest) {
                    term = term.toLowerCase();
                    var choices = [
                            @foreach($header_customers as $customer)
                        ['{{ $customer->phone }}', '{{ $customer->name }} {{ $customer->surname }}', '{{ $customer->id }}'],
                        @endforeach
                    ];
                    var suggestions = [];
                    for (i = 0; i < choices.length; i++)
                        if (~(choices[i][0] + ' ' + choices[i][1]).toLowerCase().indexOf(term)) suggestions.push(choices[i]);
                    suggest(suggestions);
                },
                renderItem: function (item, search) {
                    search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&amp;');
                    var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
                    return '<div class="autocomplete-suggestion" data-langname="' + item[0] + '" data-lang="' + item[1] + '"  data-name="' + item[2] + '" data-val="' + search + '">  ' + item[0].replace(re, "<b>$1</b>") + '</div>';
                },
                onSelect: function (e, term, item) {
                    document.getElementById('advanced-demo').value = item.getAttribute('data-langname');
                    document.getElementById('fb-root').innerHTML = 'id: ' + item.getAttribute('data-name') + ' (' + item.getAttribute('data-lang') + ') ';
                    $('.gdd').val(item.getAttribute('data-langname'));
                    $('#customer2').val(item.getAttribute('data-name'));
                    $('.rads').show();
                    $('#p1').show();
                }
            });
        }

        if ($('#advanced-demo2').length) {
            var demo2 = new autoComplete({
                selector: '#advanced-demo2',
                minChars: 2,
                source: function (term, suggest) {
                    term = term.toLowerCase();
                    var choices = [
                            @foreach($header_customers as $customer)
                        ['{{ $customer->phone }}', '{{ $customer->name }} {{ $customer->surname }}', '{{ $customer->id }}'],
                        @endforeach
                    ];
                    var suggestions = [];
                    for (i = 0; i < choices.length; i++)
                        if (~(choices[i][0] + ' ' + choices[i][1]).toLowerCase().indexOf(term)) suggestions.push(choices[i]);
                    suggest(suggestions);
                },
                renderItem: function (item, search) {
                    search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&amp;');
                    var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
                    return '<div class="autocomplete-suggestion" data-langname="' + item[0] + '" data-lang="' + item[1] + '"  data-name="' + item[2] + '" data-val="' + search + '">  ' + item[0].replace(re, "<b>$1</b>") + '</div>';
                },
                onSelect: function (e, term, item) {
                    document.getElementById('advanced-demo').value = item.getAttribute('data-langname');
                    document.getElementById('fb-root').innerHTML = 'id: ' + item.getAttribute('data-name') + ' (' + item.getAttribute('data-lang') + ') ';
                    $('#customerg').val(item.getAttribute('data-name'));
                }
            });
        }

        /*
        if ($('#advanced-demoCreateOrder').length) {
            var demo2 = new autoComplete({
                selector: '#advanced-demoCreateOrder',
                minChars: 2,
                source: function (term, suggest) {
                    term = term.toLowerCase();
                    var choices = [
                            @foreach($header_customers as $customer)
                        ['{{ $customer->phone }}', '{{ $customer->name }} {{ $customer->surname }}', '{{ $customer->id }}'],
                        @endforeach
                    ];
                    var suggestions = [];
                    for (i = 0; i < choices.length; i++)
                        if (~(choices[i][0] + ' ' + choices[i][1]).toLowerCase().indexOf(term)) suggestions.push(choices[i]);
                    suggest(suggestions);
                },
                renderItem: function (item, search) {
                    search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&amp;');
                    var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
                    return '<div class="autocomplete-suggestion" data-langname="' + item[0] + '" data-lang="' + item[1] + '"  data-name="' + item[2] + '" data-val="' + search + '">  ' + item[0].replace(re, "<b>$1</b>") + '</div>';
                },
                onSelect: function (e, term, item) {
                    document.getElementById('advanced-demoCreateOrder').value = item.getAttribute('data-langname');
                    document.getElementById('fb-root').innerHTML = 'id: ' + item.getAttribute('data-name') + ' (' + item.getAttribute('data-lang') + ')  ';
                    $('.gdd').val(item.getAttribute('data-langname'));
                    $('#customer2').val(item.getAttribute('data-name'));
                    $('.rads').show();
                    $('#p1').show();
                }
            });
        }
        */
        
    })
</script>

</body>
</html>
