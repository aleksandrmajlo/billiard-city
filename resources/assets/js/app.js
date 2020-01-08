/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

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

// ******* alert 
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);
// ******* alert end

import 'vue-search-select/dist/VueSearchSelect.css'


//************* tooltip
import {
    VTooltip,
    VPopover,
    VClosePopover
} from 'v-tooltip'
Vue.directive('tooltip', VTooltip)
Vue.directive('close-popover', VClosePopover)
Vue.component('v-popover', VPopover);

Vue.filter('two_digits', function (value) {
    if (value.toString().length <= 1) {
        return "0" + value.toString();
    }
    return value.toString();
});

Vue.component('TimerTable', require('./components/TimerTable.vue'));
Vue.component('OrderTest', require('./components/OrderTest.vue'));
Vue.component('TimerPriceorder', require('./components/TimerPriceorder.vue'));
Vue.component('IngredientButton', require('./components/Ingredient/IngredientButton.vue'));
Vue.component('IngredientAddproduct', require('./components/Ingredient/IngredientAddproduct.vue'));

Vue.component('ActForm', require('./components/Acts/ActForm.vue'));
Vue.component('DocPrint', require('./components/Print/DocPrint.vue'));

// Заказ

Vue.component('Categories', require('./components/Order/Categories.vue'));
Vue.component('Products', require('./components/Order/Products.vue'));
Vue.component('MenuOrder', require('./components/Order/MenuOrder.vue'));
Vue.component('Search', require('./components/Order/Search.vue'));
Vue.component('Cart', require('./components/Order/Cart.vue'));
Vue.component('TotalOrder', require('./components/Order/TotalOrder.vue'));
Vue.component('ButtonBlock', require('./components/Order/ButtonBlock.vue'));
Vue.component('ClearorderModal', require('./components/Order/ClearorderModal.vue'));
Vue.component('PayModal', require('./components/Order/PayModal.vue'));
Vue.component('SmsModal', require('./components/Order/SmsModal.vue'));
Vue.component('SmsCode', require('./components/Order/SmsCode.vue'));

const app = new Vue({
    el: '#app',
    store: new Vuex.Store(store)
});