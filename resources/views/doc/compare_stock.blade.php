@if(app('request')->has('title'))
    @if(strpos($stock['title'], app('request')->input('title'))!== false)

        <tr class="@if(request()->has('cat')) catstock  @endif cat_{{$stock['cat_id']}} ">
            <td>{{$stock['title']}}</td>
            <td>{{$stock['cat']}}</td>
            <td> @lang('act.product')</td>
            <td> {{$stock['count'][0]}}</td>
            <td> {{$stock['count'][1]}}</td>
        </tr>

    @endif
@else
    <tr class="@if(request()->has('cat')) catstock  @endif  cat_{{$stock['cat_id']}} ">
        <td>{{$stock['title']}}</td>
        <td>{{$stock['cat']}}</td>
        <td> @lang('act.product')</td>
        <td> {{$stock['count'][0]}}</td>
        <td> {{$stock['count'][1]}}</td>
    </tr>
@endif
