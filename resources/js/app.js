import './bootstrap';
import SporkApp from './SporkApp';

import { createApp } from 'vue';

const app = createApp({});

window.Spork = new SporkApp(app);

Spork.component('crud-view', require('./components/CrudView').default);
Spork.component('feature-required', require('./components/FeatureRequired').default);
Spork.component('dual-menu-panel', require('./components/DualMenuPanel').default);

Spork.setupStore({
    Authentication: require('./store/Authentication').default,
    Feature: require('./store/Feature').default,
})

require('@system/news/resources/app');
require('@system/finance/resources/app');
require('@system/calendar/resources/app');
require('@system/maintenance/resources/app');
require('@system/planning/resources/app');
require('@system/research/resources/app');
require('@system/shopping/resources/app');
require('@system/seeds/resources/app');

Spork.routesFor('authentication', [
    Spork.authenticatedRoute('settings', './routes/Profile/UserAccount'),
]);

Spork.build(async ({ store, router }) => {
    await store.dispatch('fetchUser');

    store.dispatch('getFeatureLists', {
        include: 'accounts',
    })
});

// const router = createRouter({
//     history: createWebHistory(),
//     routes: [
//         authenticatedRoute('/', './routes/Base', {
//             children: [
//                 authenticatedRoute('/shopping', './routes/Shopping/Shopping', {
//                     children: [
//                         authenticatedRoute('shopping/order', './routes/Shopping/PastOrders'),
//                         authenticatedRoute('shopping/cart', './routes/Shopping/Cart'),
//                         authenticatedRoute('', './routes/Shopping/Store'),
//                     ]
//                 }),
                

//                 ...(process.env.MIX_ADD_SERVER_SUPPORT === 'true' ? [
//                     authenticatedRoute('/servers', './routes/Servers/Servers', {
//                         children: [
//                             authenticatedRoute('dashboard', './routes/Servers/Dashboard'),
//                         ],
//                     }),
    
//                 ] : [])
//             ],
//         }),

//         unauthenticatedRoute('/:pathMatch(.*)', './routes/Auth/Login'),

//     ]
// })

// : {
//     Authentication,
//     CalendarStore,
//     FeatureStore,
//     MaintenanceStore,
//     PlanningStore,
//     ResearchStore,
//     ShoppingStore,
//     NewsStore,
//     Account,
//     Transaction,
//     Servers
// }
