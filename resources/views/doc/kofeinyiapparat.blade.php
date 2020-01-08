@if($act->kofeinyiapparat)
    <tr>
        <td colspan="2"><div class="text-center">@lang('act.kofeinyi_apparat')</div></td>
        <td colspan="2"><div class="text-center">{{$act->kofeinyiapparat->count}}</div></td>
    </tr>
@endif