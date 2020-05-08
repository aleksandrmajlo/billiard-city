@extends('layouts.app')
@php
  App::setLocale(session('lng'));
@endphp
@section('content')
  <section class="content-header">
    <h1>
      @lang('site.open_order')
    </h1>
  </section>

  <section class="content">
    @if (session('status'))
      <div class="alert alert-success">
        {{ session('status') }}
      </div>
    @endif
    <div class="row">
      <div class="col-xs-12">

        <div class="box">
          <div class="box-header">
            <a href="{{route('orderscreate')}}" type="button" class="btn  btn-lg btn-block btn-success" style="width: 50%;">+ @lang('site.add') </a>
          </div>

          <div class="box-body">
                  <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <th>#</th>
                        <th>@lang('site.client')</th>
                        <th>@lang('site.date_start')</th>
                        <th>@lang('site.summa')  </th>
                        <th>Примiтка </th>
                        <th style="width: 210px"> </th>
                      </tr>
                      </thead>
                      <tbody>
                      @foreach($orders as $order)
                        <tr>
                          <td><a href="/bar/{{ $order->id }}" target="_blank">{{ $order->id }}</a></td>
                          <td>
                            @if($order->customer_id == 0)
                              Гість
                            @else
                              @php
                                $customer = App\Customer::where('id', $order->customer_id)->firstOrFail();
                              @endphp
                              {{$customer->name  }}
                            @endif
                          </td>
                          <td>{{  \Carbon\Carbon::parse($order->start)->format('d-m-Y H:i')}}</td>
                          <td><b>{{ $order->amount }} ₴</b></td>
                          <th style="font-width: normal !Important;">{{ $order->info }} </th>
                          <td class="tr_button">
                                <div class="buttonOpenTableConteer">
                                    <a href="/order-closed/{{$order->id ?? 0}}" id="id3"  class="btn btn-app btn-warning "  >
                                      <i class="fa fa-unlock-alt"  ></i> @lang('orders.close')
                                    </a>
                                </div>
                          </td>
                        </tr>
                      @endforeach
                      </tbody>
                    </table>
                </div>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->


  <div class="modal fade" id="modal-default">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">@lang('site.add')</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{action('OrderController@storeSmall')}}" enctype="multipart/form-data" >
          {{ csrf_field() }}
          <input type="text" id="orderid" name="orderid" value="0" class="form-control" style="display:none" >
          <div class="row" id="raz">
            <div class="col-xs-6 ">
              <label>Товар</label>
              <select class="form-control js-example-basic-single" name="product1" id="sel1">
                <option value="" selected>-</option>
                @foreach($products as $product)
                  <option value="{{ $product->id }}">{{ $product->title }} (склад: {{ $product->count }})</option>
                @endforeach
              </select>
            </div>
            <div class="col-xs-3 ">
              <label>Порция</label>
              <p><input type="number" value="1" min="0" max="1000" step="0.1" name="count1"   placeholder="1.0" class="form-control" id="sel2"    autocomplete="no"></p>
            </div>
          </div>
            <div class="row" id="selectconnect">
              <div class="col-xs-6" id="select_apend"></div>
              <div class="col-xs-3" id="input_apend"></div>
              <div class="col-xs-2 " id="del" style="margin-top: 1px; display: block; clear: inherit;   width: 100px;"></div>
            </div>

            <div class="row" style="position:relative; top: -15px;">
              <div class="col-sm-9 col-lg-9">
                <div class="addproduct btn btn-block btn-success" id="2" style="margin-top: 26px;">+ @lang('site.add') продукт</div>
              </div>
            </div>

          <div class="" style="display: none">
            <label>@lang('site.primitka')</label>
            <textarea name="description"  class="form-control" >{{ $clients->description ?? '' }}</textarea>
          </div>
          <p><br>
            <input id="b1" type = 'submit' value = "Додати в замовлення"  class="btn btn btn-primary active" />
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Закрити</button>
        </div>

      </div>
    </div>
  </div>

  <!-- /.modal -->
  <div class="modal modal-warning fade" id="modal-warning">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Warning Modal</h4>
        </div>
        <div class="modal-body">

          <a  id="id3" href="/close-bar?id={{$order->id ?? 0}}" class="btn btn-app btn-warning ">
            <i class="fa fa-unlock-alt" data-toggle="modal" data-target="#modal-default"></i> Закрити
          </a>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-outline">Save changes</button>
        </div>
      </div>
    </div>
  </div>
@endsection
