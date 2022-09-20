/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import moment from 'moment';
import Swal from 'sweetalert2';
import IdleVue from 'idle-vue';
import VueProgressBar from 'vue-progressbar';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import vSelect from 'vue-select';
import { Form } from 'vform';
import store from './store';

require('./bootstrap');

import { createInertiaApp } from '@inertiajs/inertia-vue';

import Vue from 'vue';

window.Vue = require('vue');
window.axios = require('axios');
window.Form = Form;
window.Swal = Swal;
window.Fire = new Vue();


window.Popper = require('popper.js').default;
window.$ = window.jQuery = require('jquery');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Vue.component('v-select', vSelect);
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.use(VueProgressBar, {
    color: "rgb(255, 254, 253)",
    failedColor: "red",
    height: "2px"
});

Vue.filter('customDate', function(created_at) {
    return moment(created_at).format('lll');
});

Vue.filter('formatNumber', function(value) {
    let nf = Intl.NumberFormat();
    return nf.format(value);
});

// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue)
    // Optionally install the BootstrapVue icon components plugin
    // Vue.use(IconsPlugin)

Vue.use(IdleVue, {
    eventEmitter: new Vue,
    store,
    idleTime: 900000,
    startAtIdle: false
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

createInertiaApp({
    resolve: name => require(`./Pages/${name}`),
    setup({ el, App, props }) {
        new Vue({
            render: h => h(App, props),
            store,
        }).$mount(el)
    },
})
