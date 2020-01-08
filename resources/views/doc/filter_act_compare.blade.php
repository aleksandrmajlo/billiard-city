<form method="get">
    @if(isset($act1)&&isset($act2))
        <input type="hidden"  name="act1" value="{{request()->act1}}">
        <input type="hidden"  name="act2" value="{{request()->act2}}">
    @endif
    <div class="flexBlock">
        <div class="itemFilter flexMaxWidth">
            <div class="form-group">
                <label>@lang('act.name')</label>
                <input value="{{ app('request')->input('title') }}" type="text" name="title"
                       class="form-control">
            </div>
        </div>
        <div class="itemFilter">
            <div class="form-group">
                <label>@lang('act.cat')</label>
                <select class="form-control" name="cat">
                    <option></option>
                    @foreach($cats as $cat)
                        <option
                                @if(request()->has('cat')&&request()->cat==$cat->id)
                                selected
                                @endif
                                value="{{$cat->id}}">{{$cat->title}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="itemFilter">
            <div class="form-group">
                <label>@lang('act.type')</label>
                <select class="form-control" name="type">
                    <option></option>
                    <option
                            @if(request()->has('type')&&request()->type=='ingredient')
                            selected
                            @endif
                            value="ingredient">@lang('act.ingredient')</option>
                    <option
                            @if(request()->has('type')&&request()->type=='product')
                            selected
                            @endif
                            value="product">@lang('act.product')</option>
                </select>
            </div>
        </div>
    </div>
    <div class="flexBlock">
        <div class="itemFilter">
            <button type="submit" class="btn btn-primary">@lang('act.send')</button>
        </div>
        <div class="itemFilter">
            {{--<a href="{{url('/doc/act/'.$id)}}" class="btn btn-primary">--}}
            <a href="{{url($urlFilter)}}" class="btn btn-primary">
                @lang('act.reset')
            </a>
        </div>
    </div>
</form>