require('./bootstrap');
require('./common');
window.Vue = require('vue');

import axios from 'axios'
import VueAxios from 'vue-axios'
Vue.use(VueAxios, axios)

// vuex
import Vuex from 'vuex';
Vue.use(Vuex);
import store from './store.js';

export const eventBus = new Vue();

// ******* alert 
import Vue from 'vue';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);
// ******* alert end


// миксин
import GlobalMixin from './mixin/mixin'
Vue.mixin(GlobalMixin);

//************* tooltip
import {
    VTooltip,
    VPopover,
    VClosePopover
} from 'v-tooltip'
Vue.directive('tooltip', VTooltip)
Vue.directive('close-popover', VClosePopover)
Vue.component('v-popover', VPopover);


// фильтры 
import './filter'

// Lang
import i18n from './lang/i18n'

// таймер для стола открытого
Vue.component('TimerTable', require('./components/TimerTable.vue').default);
// таймер сверху
Vue.component('ClockHeader', require('./components/ClockHeader.vue').default);
Vue.component('MessagesHeader', require('./components/MessagesHeader.vue').default);
Vue.component('TimerPriceorder', require('./components/TimerPriceorder.vue').default);

//Booking
Vue.component('CalendarBooking', require('./components/Booking/CalendarBooking.vue').default);
Vue.component('SearchResults', require('./components/Booking/SearchResults.vue').default);
Vue.component('ReadBooking', require('./components/Booking/ReadBooking.vue').default);
Vue.component('autocomplete', require('./components/all/autocomplete.vue').default);


Vue.component('OrderTest', require('./components/OrderTest.vue').default);

Vue.component('IngredientButton', require('./components/Ingredient/IngredientButton.vue').default);
Vue.component('IngredientAddproduct', require('./components/Ingredient/IngredientAddproduct.vue').default);

Vue.component('DocPrint', require('./components/Print/DocPrint.vue').default);

// Заказ
Vue.component('Categories', require('./components/Order/Categories.vue').default);
Vue.component('Products', require('./components/Order/Products.vue').default);
Vue.component('MenuOrder', require('./components/Order/MenuOrder.vue').default);
Vue.component('Search', require('./components/Order/Search.vue').default);
Vue.component('Cart', require('./components/Order/Cart.vue').default);
Vue.component('TotalOrder', require('./components/Order/TotalOrder.vue').default);
Vue.component('ButtonBlock', require('./components/Order/ButtonBlock.vue').default);
Vue.component('ClearorderModal', require('./components/Order/ClearorderModal.vue').default);
Vue.component('PayModal', require('./components/Order/PayModal.vue').default);

Vue.component('SmsModal', require('./components/Order/SmsModal.vue').default);
Vue.component('SmsCode', require('./components/Order/SmsCode.vue').default);
Vue.component('CommentOrder', require('./components/Order/CommentOrder.vue').default);

// заказ по столу
Vue.component('TotalTable', require('./components/Ordertable/TotalTable.vue').default);

// доки
Vue.component('WriteofProducts', require('./components/Docs/WriteofProducts.vue').default);

// Столы
Vue.component('OpenTable', require('./components/Table/OpenTable.vue').default);
Vue.component('FreeTable', require('./components/Table/FreeTable.vue').default);
Vue.component('CloseTable', require('./components/Table/CloseTable.vue').default);
Vue.component('PrintTable', require('./components/Table/PrintTable.vue').default);
Vue.component('TableAdd', require('./components/Table/TableAdd.vue').default);
Vue.component('ScreenTable', require('./components/Table/ScreenTable.vue').default);

// закрытие барменом смены
Vue.component('CloseBarmen', require('./components/Change/CloseBarmen.vue').default);
// открытие барменом смены
Vue.component('OpenBarmen', require('./components/Change/OpenBarmen.vue').default);


Vue.component('ReadOrder', require('./components/Order/ReadOrder.vue').default);


const app = new Vue({
    el: '#app',
    i18n,
    store: new Vuex.Store(store)
});