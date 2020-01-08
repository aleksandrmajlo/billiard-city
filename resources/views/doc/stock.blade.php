@if(app('request')->has('title'))
   @if(strpos($stock->title, app('request')->input('title'))!== false)
        <tr class="@if(request()->has('cat')) catstock  @endif cat_{{$stock->categorySee->id}}">
            <td>{{$stock->title}}</td>
            <td>{{$stock->categorySee->title}}</td>
            <td> @lang('act.product')</td>
            <td> {{$stock ->pivot->count}}</td>
        </tr>
    @endif

@else
    <tr class="@if(request()->has('cat')) catstock  @endif cat_{{$stock->categorySee->id}}">
        <td>{{$stock->title}}</td>
        <td>{{$stock->categorySee->title}}</td>
        <td> @lang('act.product')</td>
        <td> {{$stock ->pivot->count}}</td>
    </tr>
@endif
