import './bootstrap';

import { buildUrl } from '@kbco/query-builder';

import { createApp } from 'vue';
import { createStore } from 'vuex';
import { createRouter, createWebHistory } from 'vue-router'
import CrudView from "@components/CrudView";

import dayjs from 'dayjs';

import CalendarEvent from './CalendarEvent'

import './bootstrap'
import StoreItem from "./routes/Shopping/components/StoreItem";

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
        authenticatedRoute('/', './routes/Base', {
            children: [
                authenticatedRoute('/planning', './routes/Planning/KanbanBoard'),
                authenticatedRoute('/maintenance/properties', './routes/Maintenance/Properties'),
                authenticatedRoute('/maintenance/garage', './routes/Maintenance/Garage'),
                authenticatedRoute('/calendar', './routes/Calendar/Calendar'),
                authenticatedRoute('/shopping', './routes/Shopping/Shopping', {
                    children: [
                        authenticatedRoute('shopping/order', './routes/Shopping/PastOrders'),
                        authenticatedRoute('shopping/cart', './routes/Shopping/Cart'),
                        authenticatedRoute('', './routes/Shopping/Store'),
                    ]
                }),
                
                authenticatedRoute('/research', './routes/Research/Research', {
                    children: [
                        authenticatedRoute(':id', './routes/Research/Topic'),
                        authenticatedRoute('', './routes/Research/Dashboard'),
                    ]
                }),
                authenticatedRoute('/miscellaneous', './routes/Miscellaneous/Miscellaneous', {
                    children: [
                        authenticatedRoute('', './routes/Miscellaneous/Home'),
                    ],
                }),
                authenticatedRoute('/finance', './routes/Finance/Finance', {
                    children: [
                        authenticatedRoute('dashboard', './routes/Finance/Dashboard'),
                        authenticatedRoute('settings', './routes/Finance/Settings'),
                    ]
                }),
                authenticatedRoute('/food', './routes/Food/Food', {
                    children: [
                        authenticatedRoute('dashboard', './routes/Food/Food'),
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
