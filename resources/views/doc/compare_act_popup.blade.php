<div class="win act" id="compareActWin">
    <div class="form-user acts__form">
        <p class="p"><span class="h4">@lang('act.compare')</span></p>
        <form action="{{ route('compare_act') }}" method="get">
            <div class="row">
                <div class="col-xs-12">
                    <select required  name="act1">
                        @foreach($filter_acts as $act)
                            <option  data-date="{{$act->created_at}}"
                                     data-user="@if($act->user){{$act->user->name}}@endif"
                                     data-change="{{$act->change_id}}"
                                    @if(request()->has('act1')&&request()->act1==$act->id)
                                    selected
                                    @endif
                                    value="{{$act->id}}">
                                Акт {{$act->id}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xs-4 col-xs-xs-xs-12">
                    <label>Акт</label>
                </div>
                <div class="col-xs-8 col-xs-xs-xs-12">
                    <p class="act1_title hidden">№5 от 2019-11-21 12:01:04</p>
                </div>
                <div class="col-xs-4 col-xs-xs-xs-12">
                    <label>@lang('act.worker')</label>
                </div>
                <div class="col-xs-8 col-xs-xs-xs-12">
                    <p class="act1_user hidden">Стасюк София</p>
                </div>
                <div class="col-xs-4 col-xs-xs-xs-12">
                    <label>@lang('act.smena')</label>
                </div>
                <div class="col-xs-8 col-xs-xs-xs-12">
                    <p class="act1_change hidden">173</p>
                </div>
                <div class="col-xs-12">
                    <select required name="act2">
                        @foreach($filter_acts as $act)
                            <option data-date="{{$act->created_at}}"
                                    data-user="@if($act->user){{$act->user->name}}@endif"
                                    data-change="{{$act->change_id}}"
                                    @if(request()->has('act1')&&request()->act2==$act->id)
                                    selected
                                    @endif
                                    value="{{$act->id}}">
                                Акт {{$act->id}} </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xs-4 col-xs-xs-xs-12"><label>Акт</label></div>
                <div class="col-xs-8 col-xs-xs-xs-12">
                    <p class="act2_title hidden">№12 от 2019-11-28 15:38:31</p>
                </div>
                <div class="col-xs-4 col-xs-xs-xs-12">
                    <label>@lang('act.worker')</label>
                </div>
                <div class="col-xs-8 col-xs-xs-xs-12">
                    <p class="act2_user hidden">Татьяна Давидяк</p>
                </div>
                <div class="col-xs-4 col-xs-xs-xs-12">
                    <label>@lang('act.smena')</label>
                </div>
                <div class="col-xs-8 col-xs-xs-xs-12">
                    <p class="act2_change hidden">174</p>
                </div>
                <div class="col-xs-12 equates">
                    <input class="equate" type="submit" value="@lang('act.compareButton')">
                </div>
            </div>
        </form>
    </div>
    <a id="closeCompareActWin" class="close  " title="Закрыть " href="#"></a>
</div>
