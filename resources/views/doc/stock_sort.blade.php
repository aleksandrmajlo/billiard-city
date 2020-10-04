@if(app('request')->has('title'))
    @if(strpos($stock['title'], app('request')->input('title'))!== false)
        <tr class="@if(request()->has('cat')) catstock  @endif cat_{{$stock['category_id']}}">
            <td>{{$stock['title']}}</td>
            <td>{{$stock['category_title']}}</td>
            <td> @lang('act.product')</td>
            <td> {{$stock['count']}}</td>
            @if(isset($cause))
                <td> {{$stock ->pivot->cause}}</td>
            @endif
        </tr>
    @endif
@else
    <tr class="@if(request()->has('cat')) catstock  @endif cat_{{$stock['category_id']}}">
        <td>{{$stock['title']}}</td>
        <td>{{$stock['category_title']}}</td>
        <td> @lang('act.product')</td>
        <td> {{$stock['count']}}</td>
        @if(isset($cause))
            <td> {{$stock ->pivot->cause}}</td>
        @endif
    </tr>
@endif
