<tr>
    <td>{{$product['title']}}</td>
    <td>
        @if($product['cat'])
            {{$product['cat']}}
        @endif
    </td>
    <td>
        @if($product['type']===1)
            @lang('act.product')
        @else
            @lang('act.ingredient')
        @endif

    </td>
    <td> {{$product['count']}}</td>
    @if(isset($cause))
        <td> {{$product['cause']}}</td>
    @endif
</tr>
