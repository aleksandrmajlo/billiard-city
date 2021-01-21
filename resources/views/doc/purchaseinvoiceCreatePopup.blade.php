<div class="win maxHeigth"  id="readOrderWin">
    <div class="form-user acts__form over__form">
        <p class="p">
            <span class="h4">@lang('purchaseinvoice.new')</span>
        </p>
        <form action="{{action('Docs\PurchaseinvoiceController@store')}}" method="post">
            {{ csrf_field() }}
            <purchaseinvoice-create></purchaseinvoice-create>
        </form>
    </div>
    <a id="closeCompareActWin" class="close  " title="Закрыть " href="#"></a>
</div>