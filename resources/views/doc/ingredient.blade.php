@if(app('request')->has('title'))
    @if(strpos($ingredient->title, app('request')->input('title'))!== false)
        <tr class="@if(request()->has('cat')) catstock  @endif ">
            <td>{{$ingredient->title}}</td>
            <td></td>
            <td> @lang('act.ingredient')</td>
            <td> {{$ingredient ->pivot->count}}</td>
        </tr>
    @endif
@else
    <tr class="@if(request()->has('cat')) catstock  @endif">
        <td>{{$ingredient->title}}</td>
        <td></td>
        <td> @lang('act.ingredient')</td>
        <td> {{$ingredient ->pivot->count}}</td>
    </tr>
@endif
