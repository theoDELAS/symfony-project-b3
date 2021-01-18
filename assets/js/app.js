import Vue from 'vue';
import VueRouter from 'vue-router';
import '../css/app.css';
import 'notyf/notyf.min.css';
import 'alpinejs';
import store from "./store/store";

import App from './components/App.vue';
import Blank from './components/Right/Blank.vue';
import Right from './components/Right/Right.vue';

Vue.use(VueRouter)

const routes = [
    {
        name: 'blank',
        path: '/messages/all',
        component: Blank
    },
    {
        name: 'conversation',
        path: '/conversation/:id',
        component: Right
    }
];

const router = new VueRouter({
    mode: "abstract",
    routes
})

store.commit("SET_USERNAME", document.querySelector('#app').CDATA_SECTION_NODE.username)

new Vue({
    store,
    router,
    render: h => h(App)
}).$mount('#app')

router.replace('/')
