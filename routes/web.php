<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['middleware' => 'auth'], function() {
    Route::get('/', 'HomeController@index')->name('home');

    // Routs only admin
    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('/socket', 'SocketController@index')->name('socketindex');
        Route::post('/socket-edit', 'SocketController@edit')->name('socketedit');
        Route::get('/user', 'UserController@index')->name('user');
        Route::get('/user-edit', 'UserController@create')->name('useredit');
        Route::post('/user-edits', 'UserController@edit')->name('useredits');
        Route::post('/tarif-create', 'TariffController@store')->name('tarifsstore');
        Route::get('/tarif-edit/{id}', 'TariffController@show')->name('tarifshow');
        Route::post('/tarif-edits', 'TariffController@edit')->name('tarifedit');
        Route::get('/discount', 'DiscountController@index')->name('discount');
        Route::post('/discount-create-summ', 'DiscountController@storeSummDiscount')->name('storeSummDiscount');
        Route::post('/discount-create-date', 'DiscountController@storeDateDiscount')->name('storeDateDiscount');

        Route::get('/table', 'TableController@index')->name('table');
        Route::post('/tableedit', 'TableController@edit')->name('tableedit');
        Route::get('/table-create', 'TableController@create')->name('tablecreate');
        Route::post('/table-edit', 'TableController@store')->name('tablestore');
        Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
        Route::post('register', 'Auth\RegisterController@register');
        Route::get('/stock-create', 'StockController@create')->name('stockcreate');
        Route::post('/stock-store', 'StockController@store')->name('stockstore');
        Route::post('/stock-edit', 'StockController@edit')->name('stockstore');
        Route::get('/stock', 'StockController@index')->name('indexstock');
        Route::delete('/stock/{id}', 'StockController@destroy');
        Route::delete('/disount/{id}', 'DiscountController@destroy');
        Route::delete('/user/{id}', 'UserController@destroy');
        Route::delete('/tarif/{id}', 'TariffController@destroy');
        Route::get('/tarif', 'TariffController@index')->name('tarifs');
        Route::post('/category-create', 'CategoryStockController@store')->name('categorycreate');
        Route::get('/category', 'CategoryStockController@index')->name('categoryindex');
        Route::delete('/category/{id}', 'CategoryStockController@destroy');
        Route::post('/category-edit', 'CategoryStockController@edit')->name('categoryedit');
        Route::get('/cupon', 'CuponController@index')->name('cuponindex');
        Route::get('/info-money', 'MoneyController@index')->name('moneyindex');
        Route::any('/edit-money', 'MoneyController@edit')->name('moneyedit');
        Route::delete('/customers/{id}', 'CustomerController@destroy');

    });

    Route::get('/info/{id}', 'OrderController@orderBarClosedAdmin')->name('orderBarClosedAdmin');
    Route::get('/dis/{id}', 'DiscountController@userDiscount')->name('userDiscount');
    Route::get('/change/{id}', 'ChangeController@seeChange')->name('seeChange');
    Route::post('/generateCode', 'SmsController@generateCode')->name('generateCode');
    Route::post('/checkCode', 'SmsController@checkCode')->name('checkCode');

    Route::get('/sessionLng', 'HomeController@sessionLng')->name('sessionLngorders-storeSmall');
    // User manager and barmen
    Route::delete('/book-delite/{id}', 'ReservationController@destroyBooking');
    Route::get('/change', 'ChangeController@index')->name('change');
    Route::get('/stat', 'StatisticsController@index')->name('stat');
    Route::get('/orders', 'OrderController@index')->name('orders');
    Route::get('/orders-create', 'OrderController@create')->name('orderscreate');
    Route::post('/orders-store', 'OrderController@store')->name('ordersstore');
    Route::post('/orders-edit', 'OrderController@edit')->name('ordersedit');
    Route::post('/orders-close', 'OrderController@close')->name('ordersclose');
    Route::get('/orders-table-create', 'OrderController@createOrderTable')->name('orderstablecreate');
    Route::get('/order-closed/{id}', 'OrderController@orderBarClosed')->name('orderBarClosed');
    Route::get('/orderbil-closed/{id}', 'OrderController@orderBillClosed')->name('orderBillClosed');
    Route::any('/pause', 'OrderController@pauseBill')->name('pauseBill');
    Route::post('/orders-bill-closed-order', 'OrderController@orderBillClosedOrder')->name('orderBillClosedOrder');
    Route::post('/orders-store-closed-order', 'OrderController@orderBarClosedOrder')->name('orderBarClosedOrder');
    Route::post('/orders-storeSmall', 'OrderController@storeSmall')->name('storeSmall');
    Route::post('/orders-table-store', 'OrderController@storeOrderTable')->name('orders_store_table');
    Route::get('/bar/{id}', 'OrderController@orderBar')->name('orederBar');
    Route::get('/close-bar', 'OrderController@closeBar')->name('closeBar');
    Route::get('/customers', 'CustomerController@index')->name('customers');
    Route::post('/customers-update', 'CustomerController@update')->name('customersupdate');
    Route::get('/stock', 'StockController@index')->name('stock');
    Route::post('/change-create', 'ChangeController@create')->name('changecreate');
    Route::post('/change-close', 'ChangeController@closeChange')->name('changeclosechange');
    Route::get('/booking', 'ReservationController@index')->name('booking');
    Route::get('/open-table', 'ReservationController@openTable')->name('openTable');
    Route::get('/open-bar', 'ReservationController@openOrder')->name('openOrder');
    Route::get('/customers-create', 'CustomerController@create')->name('customerscreate');
    Route::get('/customers-edit', 'CustomerController@edit')->name('customersedit');
    Route::get('/customer/{id}', 'CustomerController@see')->name('customersee');
    Route::post('/customers-store', 'CustomerController@store')->name('customersstore');
    Route::delete('/table/{id}', 'TableController@destroy');
    Route::get('/reserv-table-create', 'ReservationController@reservTable')->name('reservTable');
    Route::post('/reserv-table-add', 'ReservationController@reservTableCreate')->name('reservTableCreate');
    Route::get('/booking/{id}', 'ReservationController@reservTableSee')->name('reservTableSee');
//    Route::get('/print', 'OrderController@print')->name('print');

    //  мои роуты ***********************************
    Route::get('/printTest', 'order\Order@printTest');
    Route::get('/aprint', 'PrintController@index');
    Route::post('/aprint', 'PrintController@update');
    Route::get('/open_order', 'OrdermyController@open_order');
    Route::get('/close_order', 'OrdermyController@close_order');

    Route::group(['prefix' => 'bars', 'namespace' => 'Bars'], function(){
        Route::resource('/ingredient', 'IngredientController');
    });

    Route::group(['prefix' => 'doc', 'namespace' => 'Docs'], function(){

        Route::get('/act', 'ActController@index');
        Route::get('/act/{id}', 'ActController@show');
        Route::get('/compare', 'ActController@compare');

        Route::get('/purchaseinvoice', 'PurchaseinvoiceController@index');
        Route::get('/purchaseinvoiceCreate', 'PurchaseinvoiceController@create');
        Route::get('/purchaseinvoice/{id}', 'PurchaseinvoiceController@show');
        Route::post('purchaseinvoiceCreate', 'PurchaseinvoiceController@store');

        Route::resource('/consumableinvoice', 'ConsumableinvoiceController');

    });

    Route::group(['prefix' => 'ajax', 'namespace' => 'Ajax'], function(){
        Route::get('/priceorder', 'OrderAjax@priceorder');
        Route::post('orderCloseValidate', 'OrderAjax@orderCloseValidate');
        Route::post('orderClose', 'OrderAjax@orderClose');
        Route::post('ingredient', 'IngredientAjax@get');
    });

    Route::group(['prefix' => 'order', 'namespace' => 'Order'], function(){
        Route::get('/CategoriesGet', 'Order@CategoriesGet');
        Route::post('/ProductCategoryGet', 'Order@ProductCategoryGet');
        Route::post('/SearchProduts', 'Order@SearchProduts');
        Route::post('/Pay', 'Order@Pay');
        Route::post('/GetUsers', 'Order@GetUsers');
        Route::post('/Reserve', 'Order@Reserve');
        // получить продукты в заказе
        Route::post('/getOrder', 'Order@getOrder');
        Route::post('/OrderPrint', 'Order@SendPrint');
    });

    //  мои роуты  end **************************************************


    Route::get('/public/avatars/{filename}', function ($filename) {
        $path = storage_path('app/public/avatars/' . $filename);
        if (!File::exists($path)) {
            abort(404);
        }
        $file = File::get($path);
        $type = File::mimeType($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    });
    Route::get('/no-access', 'HomeController@noAccess')->name('noaccess');

});