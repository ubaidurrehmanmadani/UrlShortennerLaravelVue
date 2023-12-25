import './bootstrap';
import { createApp } from 'vue';
import 'bootstrap/dist/css/bootstrap.min.css';
// import 'datatables.net';
// import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';
// import 'jszip';
// import 'pdfmake';
// import 'datatables.net-buttons-bs4';
// import 'jquery';
// import 'datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css';
// import 'datatables.net-buttons/js/buttons.colVis';
// import 'datatables.net-buttons/js/buttons.flash';
// import 'datatables.net-buttons/js/buttons.html5';
// import 'datatables.net-buttons/js/buttons.print';
import UrlComponent from './components/UrlComponent.vue';
import {createRouter, createWebHistory} from "vue-router";
import axios from "axios";
// window.$ = window.jquery = require('jquery');

import VueAxios from "vue-axios";
const routes = [
    {
        path: '/',
        name: 'manage_url',
        component: UrlComponent,
    }
]
const router = createRouter({
    history: createWebHistory(),
    routes, //same --- > routes:routes
})
const app = createApp({});
app.use(VueAxios, axios);
app.use($)
app.component('url-component', UrlComponent);
app.mount('#app');

export default router

