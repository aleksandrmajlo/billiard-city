<section class="filterConteer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <form method="get">
                            <div class="flexBlock">
                                <div class="itemFilter">
                                    <div class="form-group">
                                        <label>@lang('act.start')</label>
                                        <input class="form-control dateMy"
                                               @if(app('request')->has('start')&&!empty(app('request')->input('start')))
                                               value="{{ app('request')->input('start') }}"
                                               @endif
                                               name="start">
                                    </div>
                                </div>
                                <div class="itemFilter">
                                    <div class="form-group">
                                        <label>@lang('act.end')</label>
                                        <input class="form-control dateMy"
                                               @if(app('request')->has('end')&&!empty(app('request')->input('end')))
                                               value="{{ app('request')->input('end') }}"
                                               @endif
                                               name="end">
                                    </div>
                                </div>
                                <div class="itemFilter">
                                    <div class="form-group">
                                        <label>@lang('act.worker')</label>
                                        <select class="form-control" name="user_id">
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
                            </div>

                            <div class="flexBlock">
                                <div class="itemFilter">
                                    <button type="submit" class="btn btn-primary">@lang('act.send')</button>
                                </div>
                                <div class="itemFilter">
                                    <a href="{{url($this_url)}}"
                                       class="btn btn-primary">@lang('act.reset')
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>