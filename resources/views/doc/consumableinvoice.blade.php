@extends('layouts.app')
@php App::setLocale(session('lng'));@endphp
@section('content')

    <section class="content-header">
        <h1 class="mb-10">
            @lang('сonsumableinvoice.titleOne') #{{$consumableinvoice->id}} {{$consumableinvoice->created_at}}
        </h1>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        @if($productRes)
                            <div class="box-body">
                                <div class="table-container">
                                    <div class="table-responsive">
                                        <table id="ActsTable" class=" DataTable table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <td>
                                                    @lang('act.name')
                                                </td>
    
                                                <td>
                                                    @lang('сonsumableinvoice.count')
                                                </td>
    
                                                <td>
                                                    @lang('сonsumableinvoice.price')
                                                </td>

                                                <td>
                                                    @lang('сonsumableinvoice.skidka')
                                                </td>
    
                                                <td>
                                                    @lang('сonsumableinvoice.allprice')
                                                </td>
    
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($productRes as $products)
                                                 @foreach($products as $k=>$product)
                                                     <tr>
                                                         <td>{{$product['title']}}</td>
                                                         <td>{{$product['count']  }}</td>
                                                         <td>{{$product['price']  }} грн.</td>
                                                         <td>{{$k}} %</td>
                                                         <td>{{$product['total'] }} грн.</td>
                                                     </tr>

                                                 @endforeach
                                            @endforeach
                                           </tbody>
                                            <thead>
                                               <tr>
                                                   <td colspan="2">@lang('сonsumableinvoice.allpriceProducts')</td>
                                                   <td colspan="2">{{$total}} грн.</td>
                                               </tr>
                                            </thead>
                                        </table>
    
                                         <table></table>
    
                                    </div>
                                </div>
                            </div>

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection