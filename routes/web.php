<?php

/*
| Web Routes
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/test', 'TestController@index');
// телевизор
Route::get('/tv', 'TV\TVController@ScreenTable');
// получение столов для телевизора
Route::get('/GetScreenTables', 'TV\TVController@GetScreenTables');
//запись данных с лендинга
Route::post('/booking_ajax/Lending', 'Booking\BookingAjax@Lending');

//API
Route::get('/helpapi', function () {
    return view('helpapi');
});
Route::get('/helpapi_en', function () {
    return view('helpapi_en');
});

Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Routs only admin
    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('/socket', 'SocketController@index')->name('socketindex');
        Route::post('/socket-edit', 'SocketController@edit')->name('socketedit');
        // пользователи
        Route::resource('users', 'UserController');
        //Api Kkey
        Route::resource('apitokens', 'ApitokenController');
        // пользователи end******************
        //клиенты *************************
        Route::get('/discount', 'DiscountController@index')->name('discount');
        Route::post('/discount-create-summ', 'DiscountController@storeSummDiscount')->name('storeSummDiscount');
        Route::post('/discount-create-date', 'DiscountController@storeDateDiscount')->name('storeDateDiscount');
        Route::delete('/disount/{id}', 'DiscountController@destroy');
        Route::get('/dis/{id}', 'DiscountController@userDiscount')->name('userDiscount');

        //клиенты end*************************
        Route::post('/tarif-create', 'TariffController@store')->name('tarifsstore');
        Route::get('/tarif-edit/{id}', 'TariffController@show')->name('tarifshow');
        Route::post('/tarif-edits', 'TariffController@edit')->name('tarifedit');
        Route::get('/table', 'TableController@index')->name('table');
        Route::post('/tableedit', 'TableController@edit')->name('tableedit');
        Route::get('/table-create', 'TableController@create')->name('tablecreate');
        Route::post('/table-edit', 'TableController@store')->name('tablestore');

        Route::get('/stock-create', 'StockController@create')->name('stockcreate');
        Route::post('/stock-store', 'StockController@store')->name('stockstore');
        Route::post('/stock-edit', 'StockController@edit')->name('stockstore');
        Route::get('/stock', 'StockController@index')->name('indexstock');
        Route::delete('/stock/{id}', 'StockController@destroy');


        Route::delete('/tarif/{id}', 'TariffController@destroy');
        Route::get('/tarif', 'TariffController@index')->name('tarifs');
        Route::post('/category-create', 'CategoryStockController@store')->name('categorycreate');
        Route::get('/category', 'CategoryStockController@index')->name('categoryindex');
        Route::delete('/category/{id}', 'CategoryStockController@destroy');
        Route::post('/category-edit', 'CategoryStockController@edit')->name('categoryedit');
        Route::get('/cupon', 'CuponController@index')->name('cuponindex');
        Route::get('/info-money', 'MoneyController@index')->name('moneyindex');
        Route::any('/edit-money', 'MoneyController@edit')->name('moneyedit');
    });
    Route::group(['middleware' => ['roleadminmanager']], function () {
        //клиенты *************************
        Route::get('/customer/{q}', 'CustomerController@search');
        Route::get('/customerAxios/{id}', 'CustomerController@axiosReadCustomer');
        Route::resource('customers', 'CustomerController');
    });

    Route::group(['middleware' => ['RoleAdminbarmen']], function () {
        // страница  Відкритий бар
        Route::get('/open-bar', 'OrderController@openOrder')->name('openOrder');
        //просмотр  закрыть заказ в баре
        Route::get('/order-closed/{id}', 'OrderController@orderBarClosed')->name('orderBarClosed');
        // cоздать новый заказ в баре
        Route::get('/orders-create', 'OrderController@create')->name('orderscreate');
        //сохранение заказа для бара
        Route::post('/orders-store', 'OrderController@store')->name('ordersstore');
    });

    Route::post('/generateCode', 'SmsController@generateCode')->name('generateCode');
    Route::post('/checkCode', 'SmsController@checkCode')->name('checkCode');

    Route::get('/sessionLng', 'HomeController@sessionLng')->name('sessionLngorders-storeSmall');

    // страница смены
    // открытие смены
    Route::get('/change_open', 'ChangeController@change_open')->name('change_open');


    //получение данных для закрытия и открытия смены(пока только барменом)
    Route::group(['prefix' => 'change_data', 'namespace' => 'Change'], function () {
        //получение категорий товаров
        Route::get('/category', 'CloseBarmen@category');
        Route::post('/Submit', 'CloseBarmen@Submit');
        Route::post('/OpenSubmit', 'OpenBarmen@Submit');
    });

    Route::get('changes/closeManager', 'ChangeController@closeChangeManagerView')->name('closeManagerForm');
    Route::post('changes/closeManager', 'ChangeController@closeChangeManager')->name('closeManager');
    Route::get('changes/closeBarmen', 'ChangeController@closeBarmenFormView')->name('closeBarmenForm');

    Route::resource('changes', 'ChangeController');

    //    Route::get('/change', 'ChangeController@index')->name('change');
    // информация о смене
    //    Route::get('/change/{id}', 'ChangeController@seeChange')->name('seeChange');
    //    Route::post('/change-create', 'ChangeController@create')->name('changecreate');
    //    Route::post('/change-close', 'ChangeController@closeChange')->name('changeclosechange');

    //закрытие смены
    Route::get('/close_order', 'Change\CloseBarmen@close_change')->name('close_change');


    // история заказов
    Route::get('/history_orders', 'StatisticsController@index')->name('history_orders');
    // информация о заказе
    Route::get('/info/{id}', 'StatisticsController@OrderInfo')->name('order');


    Route::post('/orders-edit', 'OrderController@edit')->name('ordersedit');
    Route::post('/orders-close', 'OrderController@close')->name('ordersclose');


    Route::any('/pause', 'OrderController@pauseBill')->name('pauseBill');
    Route::post('/orders-bill-closed-order', 'OrderController@orderBillClosedOrder')->name('orderBillClosedOrder');
    Route::post('/orders-store-closed-order', 'OrderController@orderBarClosedOrder')->name('orderBarClosedOrder');
    Route::post('/orders-storeSmall', 'OrderController@storeSmall')->name('storeSmall');
    Route::post('/orders-table-store', 'OrderController@storeOrderTable')->name('orders_store_table');

    Route::get('/bar/{id}', 'OrderController@orderBar')->name('orederBar');
    Route::get('/close-bar', 'OrderController@closeBar')->name('closeBar');
    Route::get('/close-table', 'OrderController@closeTable')->name('closeTable');

    Route::get('/stock', 'StockController@index')->name('stock');

    // бронирование **************************************************
    Route::get('/booking', 'BookingController@index')->name('booking');
    Route::group(['prefix' => 'booking_ajax', 'namespace' => 'Booking'], function () {
        //получение столов товаров
        Route::get('/tables', 'BookingAjax@tables');
        //получение брони
        Route::post('/bookings', 'BookingAjax@bookings');
        //получение брони для календаря
        Route::get('/calendar_bookings', 'BookingAjax@calendar_bookings');
        //поиск брони
        Route::post('/searchBooking', 'BookingAjax@searchBooking');
        //удаление бронирования
        Route::post('/removeBooking', 'BookingAjax@removeBooking');
        // cохрание брони
        Route::post('/saveBooking', 'BookingAjax@saveBooking');
        //добавление нового стола
        Route::post('/addNewbooking', 'BookingAjax@addNewbooking');
    });
    // бронирование **************************************************

    Route::delete('/table/{id}', 'TableController@destroy');

    //  мои роуты ***********************************
    Route::get('/aprint', 'PrintController@index');
    Route::post('/aprint', 'PrintController@update');


    Route::group(['prefix' => 'bars', 'namespace' => 'Bars'], function () {
        Route::resource('/ingredient', 'IngredientController');
    });

    Route::group(['prefix' => 'doc', 'namespace' => 'Docs'], function () {

        Route::get('/act', 'ActController@index');
        Route::get('/act/{id}', 'ActController@show');
        Route::get('/act/export/{id}', 'ActController@export');

        Route::get('/compare', 'ActController@compare')->name('compare_act');
        Route::get('/compareexport', 'ActController@compareexport');

        Route::post('/setCategoryDocSortOrder', 'ActController@setCategoryDocSortOrder')->name('setCategoryDocSortOrder');
        Route::post('/setTypeDocSortOrder', 'ActController@setTypeDocSortOrder')->name('setTypeDocSortOrder');

        Route::resource('/purchaseinvoice', 'PurchaseinvoiceController');
        Route::get('/purchaseinvoice/export/{id}', 'PurchaseinvoiceController@export');

        Route::resource('/consumableinvoice', 'ConsumableinvoiceController');
        Route::get('/consumableinvoice/export/{id}', 'ConsumableinvoiceController@export');

        Route::resource('/writeof', 'WriteofController');
        //получить продукты
        Route::get('/getProducts', 'WriteofController@getProducts');
        Route::get('/writeof/export/{id}', 'WriteofController@export');
    });

    Route::group(['prefix' => 'ajax', 'namespace' => 'Ajax'], function () {
        Route::get('/priceorder', 'OrderAjax@priceorder');

        Route::post('ingredient', 'IngredientAjax@get');
        // получить пользователей для подсказки
        Route::get('CusstomerGet', 'CusstomerAjax@get');
        // установить положение сайтбара
        Route::post('setSidebarToggle', 'CusstomerAjax@setSidebarToggle');
    });

    Route::group(['prefix' => 'order', 'namespace' => 'Order'], function () {

        Route::get('/CategoriesGet', 'Order@CategoriesGet');
        Route::post('/ProductCategoryGet', 'Order@ProductCategoryGet');
        Route::post('/SearchProduts', 'Order@SearchProduts');
        // оплата бара
        Route::post('/Pay', 'Order@Pay');
        // оплата стола
        Route::post('/PayTable', 'Order@PayTable');

        // получить клиентов
        Route::post('/GetUsers', 'Order@GetUsers');
        // получить информацию о заказе
        Route::get('/InfoOrders', 'OrderInfo@InfoOrders');

        //сохранить отредактированный заказ
        Route::post('/ReadOrders', 'OrderInfo@ReadOrders');

        Route::post('/Reserve', 'Order@Reserve')->name('AddReserve');
        // получить продукты в заказе
        Route::post('/getOrder', 'Order@getOrder');
        Route::post('/SendPrint', 'Order@SendPrint');
        // запись инфы
        Route::post('/setInfo', 'Order@setInfo');
        //
    });
    //столы
    Route::group(['prefix' => 'table', 'namespace' => 'Table'], function () {

        Route::get('/open_table', 'TableController@index')->name('open_table');
        Route::get('/getTables', 'TableController@getTables');
        // получение цены стола пауз
        Route::post('/GetTablePrice', 'TableController@GetTablePrice');
        // установка паузы
        Route::post('/SetPause', 'TableController@SetPause');
        // открытие стола
        Route::post('/AddTable', 'TableController@AddTable');
        // проверить или менеджер открыл смену
        Route::post('/openChangeId', 'TableController@openChangeId');
    });

    Route::group(['prefix' => 'analytic', 'namespace' => 'Analytic'], function () {
        Route::get('/attendance', 'AnalyticController@attendance');

        // получение данных для посещаемости по месяцам
        Route::post('/attendanceData', 'AnalyticController@attendanceData');
        // получение данных для посещаемости по дням
        Route::post('/attendanceDate', 'AnalyticController@attendanceDate');

        //
        Route::post('/Billiards profitability', 'AnalyticController@attendanceDate');
    });


    //  мои роуты  end **************************************************

    Route::get('/no-access', 'HomeController@noAccess')->name('noaccess');
});
