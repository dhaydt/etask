import Alpine from "alpinejs";

global.jQuery = require("jquery");
var $ = global.jQuery;
window.$ = $;

window.Alpine = Alpine;

Alpine.start();
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");
// import bootstrapvue from "bootstrap-vue";
import Vue from "vue";
import ToastPlugin from "vue-toast-notification";
import ObjectToFD from "vue-object-to-formdata";

import "vue-toast-notification/dist/theme-sugar.css";
const VueUploadComponent = require("vue-upload-component");

window.Vue = require("vue").default;

Vue.use(ObjectToFD);
Vue.component("file-upload", VueUploadComponent);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
Vue.component(
    "main-component",
    require("./components/MainComponent.vue").default
);
// Vue.use(bootstrapvue);
Vue.use(ToastPlugin);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.mixin({
    methods: {
        callTitle() {
            console.log("code Hit");
        },
    },
});

const app = new Vue({
    el: "#app",
});
