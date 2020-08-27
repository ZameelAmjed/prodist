import VueBarcodeScanner from 'vue-barcode-scanner';
import { CChartBar } from '@coreui/coreui-vue-chartjs'
import Autocomplete from 'vuejs-auto-complete'
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
/*window.datetimepicker = require('tempusdominus-bootstrap-4')($);

$('.date').datetimepicker({
    format: 'YYYY-MM-DD',
    viewMode: 'years',
    locale: 'en',
    sideBySide: true
})*/
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
Vue.component('CChartBar', CChartBar);
Vue.use(VueBarcodeScanner,{sound:true});

const app = new Vue({
    el: '#vueapp',
    components: {
        Autocomplete,
    },
    data:{
        city:window.__FORM__ ||'',
        region:'s',
    },
    method:{
        foo(x){
           alert()
        }
    }
});

