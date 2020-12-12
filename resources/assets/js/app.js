import VueBarcodeScanner from 'vue-barcode-scanner';
import { CChartBar } from '@coreui/coreui-vue-chartjs'
import Autocomplete from 'vuejs-auto-complete'
import { Form, HasError, AlertError } from 'vform'

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue').default);
Vue.component('RewardsPusher', require('./components/rewards-pusher.vue').default);
Vue.component('BulkRewardsPusher', require('./components/bulk-rewards-pusher.vue').default);
Vue.component('AutoInput', require('./components/auto-input.vue').default);
Vue.component('auto-city', require('./components/auto-city.vue').default);
Vue.component('auto-bank', require('./components/auto-bank.vue').default);
Vue.component('auto-sales-demographics', require('./components/auto-sales-demographics.vue').default);
Vue.component('products-supplier-order', require('./components/products-supplier-order.vue').default);
Vue.component('create-order', require('./components/create-order.vue').default);
Vue.component('edit-order', require('./components/edit-order.vue').default);
Vue.component('grn-items-update', require('./components/grn-items-update.vue').default);
Vue.component('CChartBar', CChartBar);
Vue.component('modal', require('./components/modal-component.vue').default);
Vue.component('payment-modal', require('./components/payment-order-modal.vue').default);
Vue.component('empty-results', require('./components/empty-results.vue').default);
Vue.component('bulk-payment', require('./components/bulk-payment.vue').default);
Vue.component('payments-home-tabs', require('./components/payments-home-tabs.vue').default);
Vue.use(VueBarcodeScanner,{sound:true});

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

const app = new Vue({
    el: '#vueapp',
    components: {
        Autocomplete,
    },
    data:{
        city:window.__FORM__ ||'',
        region:'s',
        supplier:window.__FORM__ ||'1',
        showModal: false
    },
    method:{
        foo(x){
           alert()
        }
    }
});

