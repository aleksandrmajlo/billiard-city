@extends('layouts.app')
@php
  App::setLocale(session('lng'));
@endphp
@section('content')
  <section class="content-header">
    <h1>
      @lang('site.discount')
    </h1>


  </section>

  <section class="content">  @if (session('status'))
      <div class="alert alert-success">
        {{ session('status') }}
      </div>    @endif
    <div class="row">
      <div class="col-xs-12">

        <div class="box">

          <!-- /.box-header -->
          <div class="box-body">
            <b>Додати знижку по днях (від дати реєстрації):
            </b>
            <div class="row">
              <form method="POST" action="{{action('DiscountController@storeDateDiscount')}}" enctype="multipart/form-data" />
            {{ csrf_field() }}
              <div class="col-xs-6"><input type="integer" name="date" class="form-control" placeholder="Дней кол." required></div>
              <div class="col-xs-6"><input type="number" min="0" max="100"  name="discount" class="form-control" placeholder="%" required></div>
              <div class="col-xs-5">
                <input type="checkbox" id="scales" name="add_register">
                <label for="scales">Додавати до знижки яка вже є у клієнта (реєстрація)</label>

                <br><input type="checkbox" id="scales2" name="add_sum">
                <label for="scales2">Додавати до знижки (від суми витраченої)</label>

                <br><input type="checkbox" id="scales3" name="add_bill">
                <label for="scales3">Враховувати більярдна</label>

                <br><input type="checkbox" id="scales4" name="add_bar">
                <label for="scales4">Враховувати бар</label>
              </div>

              <div class="col-xs-12"><input type="submit" value="@lang('site.add')" class="btn btn-primary active" ></div>


              </form>

            </div> <hr>
            <br><b>Знижка від суми витраченої:</b>
            <div class="row">
              <form method="POST" action="{{action('DiscountController@storeSummDiscount')}}" enctype="multipart/form-data" />
              {{ csrf_field() }}
              <div class="col-xs-6"><input type="integer" name="summ" class="form-control" placeholder="Потраченно сумму" required></div>
              <div class="col-xs-6"><input type="number" min="0" max="100" name="discount" class="form-control" placeholder="%" required></div>
              <div class="col-xs-5">
                <label for="scales5"><input type="checkbox" id="scales5" name="add_register2"
                >
                 Додавати до знижки яка вже є у клієнта (реєстрація)</label>
                <br><label for="scales6"><input type="checkbox" id="scales6" name="add_day"
                >
                 Додавати до знижки (по дням)</label>

                <br><label for="scales7"><input type="checkbox" id="scales7" name="add_bill2"
                >
                  Враховувати більярдна</label>

                <br>
                <label for="scales8">
                <input type="checkbox" id="scales8" name="add_bar2"
                >
                  Враховувати бар</label>
              </div>

              <div class="col-xs-12"><input type="submit" value="@lang('site.add')" class="btn btn-primary active" ></div>
              </form>
            </div>
            <br>
            <h2>Знижки</h2>
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>Тип</th>

                <th>Знижка</th>
                <th>Додавати до % профiля</th>
                <th>Сумувати два типа</th>
                <th>Враховувати більярдна</th>
                <th>Враховувати бар</th>
                <th> </th>
              </tr>
              </thead>
              <tbody>
                @foreach($disounts as $disount)
                <tr>
                  <td><b>@if(isset($disount->day)) Знижка від {{ $disount->day }} днів@else Знижка від суми   {{ $disount->summ }} грн.@endif</b></td>
                  <td>{{ $disount->discount }} % </td>
                  <td>@if(isset($disount->add_register)) <i class="fa fa-fw fa-plus-square"></i> @else <i class="fa fa-fw fa-minus-square"></i> @endif </td>
                  <td>
  @if(isset($disount->summ))
   @if(isset($disount->add_day)) <i class="fa fa-fw fa-plus-square"></i> @else <i class="fa fa-fw fa-minus-square"></i> @endif
@else
                          @if(isset($disount->add_sum)) <i class="fa fa-fw fa-plus-square"></i> @else <i class="fa fa-fw fa-minus-square"></i> @endif
                      @endif
</td>
<td>@if(isset($disount->add_bill)) <i class="fa fa-fw fa-plus-square"></i> @else <i class="fa fa-fw fa-minus-square"></i> @endif    </td>
<td>@if(isset($disount->add_bar)) <i class="fa fa-fw fa-plus-square"></i> @else <i class="fa fa-fw fa-minus-square"></i> @endif    </td>
<td>
 <form action="{{ url('disount' , $disount->id ) }}" method="POST">
   {{ csrf_field() }}
   {{ method_field('delete') }}
   <button class="btn btn-block btn-primary btn-sm btn-danger" type="submit">@lang('site.del')</button>
 </form>
</td>
</tr>
@endforeach
</tbody>
</table>
<h2>Користувачі зі знижкою при реєстрації</h2>
<table id="example1" class="table table-bordered table-striped">
<thead>
<tr>
<th>Тип</th>
<th>Клiент</th>
<th>Знижка</th>
<th> </th>
</tr>
</thead>
<tbody>
@foreach($usersWhewDiscounts as $usersWhewDiscount)
<tr>
<td>{{ $usersWhewDiscount->id }}</td>
<td><a href="/customer/{{ $usersWhewDiscount->id }}">{{ $usersWhewDiscount->name }}</a></td>
<td>{{ $usersWhewDiscount->skidka }} %</td>

<td><a href="/customers-create?id={{ $usersWhewDiscount->id }}" class="btn btn-primary delete">@lang('site.edit')</a></td>
</tr>
@endforeach
{{ $usersWhewDiscounts->links() }}
</tbody>
</table>
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



@endsection
