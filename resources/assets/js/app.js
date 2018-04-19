require('../../../../../../resources/assets/js/bootstrap');
require('../../../../../../vendor/acciocms/core/src/resources/assets/js/base-components');
require('vue-multiselect/dist/vue-multiselect.min.css');

import Vue from 'vue'
import VueRouter from 'vue-router';
import { store } from './store';
import Base from '../../views/backend/Base.vue';
import All from '../../views/backend/All.vue';
import Edit from '../../views/backend/Edit.vue';

const routes = [
    { path: globalProjectDirectory+'/:adminPrefix/:lang/plugins/accio/ads-manager', component: Base, children: [
        { path: '', component: All, name: 'list' },
        { path: 'edit', component: Edit, name: 'create' },
        { path: 'edit/:adID', component: Edit, name: 'update' },
    ]}
];

Vue.use(VueRouter);
const router = new VueRouter({
    mode: 'history',
    routes: routes
});

const app = new Vue({
    el: '#app',
    router,
    store,
});
