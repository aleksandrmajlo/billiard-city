@extends('layouts.app')
@section('content')
    @php
        App::setLocale(session('lng'));
    @endphp
 <section class="content-header">
    <h1>
        @lang('site.zakaz')
    </h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">

            <form method="POST" action="{{action('OrderController@storeOrderTable')}}" enctype="multipart/form-data" >
            {{ csrf_field() }}
              <div class="row" id="raz">
                  <div class="col-xs-12">

                      <select class="form-control" name="table">
                          @foreach($tables as $table)
                              <option value="{{ $table->id }}">{{ $table->title }}</option>
                          @endforeach
                      </select>
                  </div>
                  <div class="col-xs-12" style="margin-top: 10px;">
                      <div class="row">
                          <div class="col-xs-6">
                              <label>@lang('site.client')</label>

                              <input id="advanced-demo" class="form-control"   type="text" name="q" placeholder="Начните вводить телефон клиента ..." style="width:100%; max-width:600px;outline:0">

                              <div id="fb-root"> </div>


                          </div>
                          <div class="col-xs-6" style="margin-top: 25px;">
                              <a href="{{route('customerscreate')}}" type="button" class="btn btn-block btn-success" style="width: 200px;">+ @lang('site.add')</a>
                          </div>
                      </div>
                  </div>

              </div>
              <div class="row" style="margin-top: 19px;">
                  <div class="col-xs-12">

                      <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                          <input type='text' value="{{ \Carbon\Carbon::now() }}" name="booking_from" autocomplete="off" class="form-control datepicker-here" data-date-format="yyyy-mm-dd" data-time-format='hh:ii' data-timepicker="true" data-position="bottom left" />
                      </div>
                  </div>


              </div>

            <div class="" style="display: none">
            <label>@lang('site.primitka')</label>
            <textarea name="description"  class="form-control" >{{ $clients->description ?? '' }}</textarea>
            </div>
            <p><br><input type = 'submit' value = "@lang('site.sozdat')"  class="btn btn-primary active" />
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

 <script>
     var demo2 = new autoComplete({
         selector: '#advanced-demo',
         minChars: 2,
         source: function(term, suggest){
             term = term.toLowerCase();
             var choices = [
                     @foreach($customers as $customer)
                 ['{{ $customer->phone }}', '{{ $customer->name }} {{ $customer->surname }}', '{{ $customer->id }}'],
                 @endforeach
             ];
             var suggestions = [];
             for (i=0;i<choices.length;i++)
                 if (~(choices[i][0]+' '+choices[i][1]).toLowerCase().indexOf(term)) suggestions.push(choices[i]);
             suggest(suggestions);
         },
         renderItem: function (item, search){
             search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&amp;');
             var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
             return '<div class="autocomplete-suggestion" data-langname="'+item[0]+'" data-lang="'+item[1]+'"  data-name="'+item[2]+'" data-val="'+search+'">  '+item[0].replace(re, "<b>$1</b>")+'</div>';
         },
         onSelect: function(e, term, item){
             console.log('('+item.getAttribute('data-lang')+')" selected by '+(e.type == 'keydown' ? 'pressing enter' : 'mouse click')+'.');
             document.getElementById('advanced-demo').value = item.getAttribute('data-langname');
             document.getElementById('fb-root').innerHTML = 'id: ' + item.getAttribute('data-name')+' ('+item.getAttribute('data-lang')+') <a href="asda">Отправить код</a>';
         }
     });
 </script>
@endsection
