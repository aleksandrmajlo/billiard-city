<div class="filter-block">
    <h4>@lang('act.filtr')</h4>
    <form class="filter-form" method="get">
        <div class="row">

            <div class="col-xs-4 col-xs-xs-xs-12"><label>@lang('site.title')</label></div>
            <div class="col-xs-8 col-xs-xs-xs-12">
                <input type="text"
                       @if(app('request')->has('searchtitle')&&!empty(app('request')->input('searchtitle')))
                       value="{{ app('request')->input('searchtitle') }}"
                       @endif
                       name="searchtitle"   >
            </div>



            <div class="col-xs-4 col-xs-xs-xs-12"><label>@lang('site.Category')</label></div>
            <div class="col-xs-8 col-xs-xs-xs-12">
                <select name="type">
                    <option></option>
                    @foreach($categoryStocks as $categoryStock)
                        <option
                                @if(request()->has('type')&&request()->type==$categoryStock->id)
                                selected
                                @endif
                                value="{{ $categoryStock->id }}">{{  $categoryStock->title }}</option>
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

