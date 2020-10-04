<div class="win maxHeigth"  id="readOrderWin">
    <div class="form-user acts__form over__form">
        <p class="p"><span class="h4">@lang('writeof.new')</span></p>
        <form action="{{action('Docs\WriteofController@store')}}" method="post">
            {{ csrf_field() }}
            <writeof-products></writeof-products>
        </form>
    </div>
    <a id="closeCompareActWin" class="close  " title="Закрыть " href="#"></a>
</div>