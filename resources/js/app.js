import './bootstrap';

import { buildUrl } from '@kbco/query-builder';

import { createApp } from 'vue';
import { createStore } from 'vuex';
import { createRouter, createWebHistory } from 'vue-router'
import CrudView from "./components/CrudView";

import dayjs from 'dayjs';

import CalendarEvent from './CalendarEvent'

import './bootstrap'
import StoreItem from "./components/Shopping/components/StoreItem";

dayjs.extend(require('dayjs/plugin/utc'))
dayjs.extend(require('dayjs/plugin/localizedFormat'));
dayjs.extend(require('dayjs/plugin/relativeTime'));
dayjs.extend(require('dayjs/plugin/isToday'));

window.dayjs = dayjs;
const CalendarStore = require("./store/Calendar").default;
const FeatureStore = require("./store/Feature").default;
const MaintenanceStore = require("./store/Maintenance").default;
const PlanningStore = require("./store/Planning").default;
const ResearchStore = require("./store/Research").default;
const ShoppingStore = require("./store/Shopping").default;
const NewsStore = require("./store/News").default;
const Authentication = require("./store/Authentication").default;
const Account = require("./store/Account").default;
const Transaction = require('./store/Transaction').default;

window.buildUrl = buildUrl;
window.CalendarEvent = CalendarEvent;
window.createArray = (items) => {
    let l = [];
    for (let i = 0; i < items; i ++) l.push(i + 1)
    return l;
};

const store = createStore({
    modules: {
        Authentication,
        CalendarStore,
        FeatureStore,
        MaintenanceStore,
        PlanningStore,
        ResearchStore,
        ShoppingStore,
        NewsStore,
        Account,
        Transaction,
    }
});

const route = (path, pathToComponent, extraOptions = {}) => {
    const routeComponent = require(pathToComponent+ "").default;

    return Object.assign({
        path,
        component: routeComponent,
        props: true,
    }, Object.assign(extraOptions, {
        meta: Object.assign({
            name: routeComponent.name
        }, (extraOptions.meta || {}))
    }));
}

const unauthenticatedRoute = (path, pathToComponent, extraOptions = {}) => route(path, pathToComponent, Object.assign({
    meta: {
        forceAuth: false,
    }
}, extraOptions));

const authenticatedRoute = (path, pathToComponent, extraOptions = {}) => {
    return route(path, pathToComponent, Object.assign({
        meta: {
            forceAuth: true,
        }
    }, extraOptions))
};

const router = createRouter({
    history: createWebHistory(),
    routes: [
        authenticatedRoute('/', './components/Base', {
            children: [
                authenticatedRoute('/planning', './components/Planning/KanbanBoard'),
                authenticatedRoute('/maintenance/properties', './components/Maintenance/Properties'),
                authenticatedRoute('/maintenance/garage', './components/Maintenance/Garage'),
                authenticatedRoute('/calendar', './components/Calendar/Calendar'),
                authenticatedRoute('/shopping', './components/Shopping/Shopping', {
                    children: [
                        authenticatedRoute('shopping/order', './components/Shopping/PastOrders'),
                        authenticatedRoute('shopping/cart', './components/Shopping/Cart'),
                        authenticatedRoute('', './components/Shopping/Store'),
                    ]
                }),
                authenticatedRoute('/research', './components/Research/Research', {
                    children: [
                        authenticatedRoute(':id', './components/Research/Topic'),
                        authenticatedRoute('', './components/Research/Dashboard'),
                    ]
                }),
                authenticatedRoute('/miscellaneous', './components/Miscellaneous/Miscellaneous', {
                    children: [
                        authenticatedRoute('', './components/Miscellaneous/Home'),
                    ],
                }),
                authenticatedRoute('/finance', './components/Finance/Finance', {
                    children: [
                        authenticatedRoute('dashboard', './components/Finance/Dashboard'),
                        authenticatedRoute('settings', './components/Finance/Settings'),
                    ]
                }),
                authenticatedRoute('/food', './components/Food/Food', {
                    children: [
                        authenticatedRoute('dashboard', './components/Food/Food'),
                    ],
                }),

            ],
        }),

        unauthenticatedRoute('/:pathMatch(.*)', './routes/Auth/Login'),

    ]
})

const bootApp = async () => {
    const app = createApp({});

    app.component('crud-view', CrudView);
    app.component('store-item', StoreItem);

    app.use(router)
    app.use(store)
    await axios.get('/sanctum/csrf-cookie');
    await store.dispatch('fetchUser');

    store.dispatch('getFeatureLists', {
        include: 'accounts',
    })

    if (!store.getters.isAuthenticated) {
        router.push('/login');
    }

    app.mount('#app');
};

bootApp();
