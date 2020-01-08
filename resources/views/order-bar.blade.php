@extends('layouts.app')

@section('content')
  @if (session('status'))
    <div class="alert alert-success">
      {{ session('status') }}
    </div>
  @endif
  
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
            <div class="table-container">
              <table id="example1" class="table table-bordered table-striped" style="width: 60%;">
              <thead>
              <tr>
                <th>Товар</th>
                <th>@lang('site.cina')</th>
                <th>Шт.</th>
                <th>@lang('site.summa')</th>
              </tr>
              </thead>
              <tbody>
                @foreach($products as $product)
                <tr>
                  <td>

                      @php
                        $prduct = App\Stock::where('id', $product->product_id)->firstOrFail();
                      @endphp

                    {{$prduct->title }}

                  </td>

                  <td> {{ $prduct->price }} грн.</td>
                  <td> {{ $product->count }} </td>
                  <td>
                    @php
                      $pricesum[] = $product->count * $prduct->price;
                    @endphp
                    {{ $product->count * $prduct->price}} грн.</td>
                </tr>
                @endforeach

              </tbody>
            </table>
            </div>
            <h3>Всего: @php echo array_sum($pricesum); @endphp грн.</h3>
            <h3>Знижка: 0%</h3>
            <hr>
            <h3>@lang('site.itogo'): @php echo array_sum($pricesum); @endphp грн.</h3>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection