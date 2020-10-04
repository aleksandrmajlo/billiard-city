<script>
    Globalstocks =@php echo json_encode($stocks); @endphp;
    Globalingredients =@php echo json_encode($ingredients); @endphp;
</script>
@extends('layouts.app')
@section('content')
    <div class="user">
        <div class="user__title">
            <h2>@lang('purchaseinvoice.new')</h2>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <form method="post" action="{{action('Docs\PurchaseinvoiceController@store')}}">
                            {{ csrf_field() }}
                            {{-- складова start--}}
                            <div class="formGroup">
                                <div class="addPurchaseinvoiceHeader addPurchaseinvoiceItem">
                                    <div class="formItem formItemInp">
                                        <strong>@lang('purchaseinvoice.titleCreateIng')</strong>
                                    </div>
                                    <div class="formItem formItemInp">
                                        <strong>@lang('purchaseinvoice.count')</strong>
                                    </div>
                                </div>
                                <div id="addPurchaseinvoiceItem"></div>
                            </div>
                            <div class="form-group">
                                <a class="btn btn-success" href="#addProdIng"
                                   id="addProdIng">@lang('purchaseinvoice.addProdIng')</a>
                            </div>
                            <hr>
                            {{-- складова end--}}
                            {{-- product start--}}
                            <div class="formGroup">
                                <div class="addPurchaseinvoiceHeader addPurchaseinvoiceItem">
                                    <div class="formItem formItemInp">
                                        <strong>@lang('purchaseinvoice.titleCreateProduct')</strong>
                                    </div>
                                    <div class="formItem formItemInp">
                                        <strong>@lang('purchaseinvoice.count')</strong>
                                    </div>
                                </div>
                                <div id="addPurchaseProduct"></div>
                            </div>
                            <div class="form-group">
                                <a class="btn btn-success" href="#" id="addProd">@lang('purchaseinvoice.addProd')</a>
                            </div>
                            <hr>
                            {{-- product end  --}}
                            <button type="submit" class="btn-primary btn btn-lg">@lang('purchaseinvoice.add')</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <div class="hidden" id="invoiceItemtmpl">
            <div class="addPurchaseinvoiceItem">
                <div class="formItem formItemInp">
                    <div class="form-group">
                        <select class="form-control select2Din" name="id_ingredients[]">
                            @foreach($ingredients as $key=>$ingredient)
                                <option value="{{$key}}">{{$ingredient}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="formItem formItemInp">
                    <div class="form-group">
                        <input class="form-control" required type="number" min="0" step="0.01"
                               name="count_ingredients[]">
                    </div>
                </div>
                <div class="formItem">
                    <div class="form-group">
                        <a href="#" class="removeItem">
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden" id="productItemtmpl">
            <div class="addPurchaseinvoiceItem">
                <div class="formItem formItemInp">
                    <div class="form-group">
                        <select class="form-control select2Din" name="id_stocks[]">
                            @foreach($stocks as $ingredient)
                                @if($ingredient->categorySee->id!=$kofeinyi_apparat_category_id)
                                    <option value="{{$ingredient->id}}">{{$ingredient->title}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="formItem formItemInp">
                    <div class="form-group">
                        <input class="form-control" required type="number" min="0"
                               step="0.01"
                               name="count_stocks[]">
                    </div>
                </div>
                <div class="formItem">
                    <div class="form-group">
                        <a href="#" class="removeItem">
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection