<div class="filter-block">
    <h4>@lang('act.filtr')</h4>
    <form class="filter-form" method="get">
        <div class="row">
            <div class="col-xs-4 col-xs-xs-xs-12"><label>@lang('act.start')</label></div>
            <div class="col-xs-8 col-xs-xs-xs-12">
                <input type="date"
                       @if(app('request')->has('start')&&!empty(app('request')->input('start')))
                       value="{{ app('request')->input('start') }}"
                       @endif
                       name="start"
                       placeholder="ДД.ММ.ГГГГ">
            </div>

            <div class="col-xs-4 col-xs-xs-xs-12"><label>@lang('act.end')</label></div>
            <div class="col-xs-8 col-xs-xs-xs-12">
                <input type="date"
                       placeholder="ДД.ММ.ГГГГ"
                       @if(app('request')->has('end')&&!empty(app('request')->input('end')))
                       value="{{ app('request')->input('end') }}"
                       @endif
                       name="end">
            </div>

            <div class="col-xs-4 col-xs-xs-xs-12"><label>@lang('act.worker')</label></div>
            <div class="col-xs-8 col-xs-xs-xs-12">
                <select name="user_id">
                    <option></option>
                    @foreach($users as $user)
                        <option @if(request()->has('user_id')&&request()->user_id==$user->id)
                                selected
                                @endif
                                value="{{$user->id}}">{{$user->name}}</option>
                    @endforeach
                </select>
            </div>

        </div>
        <div class="buttons">
            <a class="ResetLink" href="{{url($this_url)}}"
               class="">@lang('act.reset')
            </a>
            <button>@lang('act.send')</button>
        </div>
    </form>
</div>

