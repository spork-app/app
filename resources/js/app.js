import './bootstrap';
import SporkApp from './SporkApp';
import Toaster from "@meforma/vue-toaster";

import { createApp } from 'vue';

const app = createApp({});
app.use(Toaster);

window.Spork = new SporkApp(app);

Spork.component('crud-view', require('./components/CrudView').default);
Spork.component('feature-required', require('./components/FeatureRequired').default);
Spork.component('dual-menu-panel', require('./components/DualMenuPanel').default);

Spork.setupStore({
    Authentication: require('./store/Authentication').default,
    Feature: require('./store/Feature').default,
    ActivityLog: require('./store/ActivityLog').default,
})


Spork.build(async ({ store, router }) => {
    await axios.get('/sanctum/csrf-cookie');
    await store.dispatch('fetchUser');
    if (!store.getters.isAuthenticated && router.currentRoute.path !== '/login') {
        router.push('/login');
        return;
    }
    store.dispatch('getFeatureLists', {
        include: 'accounts',
    })

    store.dispatch('fetchLogs', {
        filter: {
            subject_type: 'Spork\\Greenhouse\\Models\\Plant'
        },
        include: 'causer,subject',
    })
});


require('@system/news/resources/app');
require('@system/finance/resources/app');
require('@system/calendar/resources/app');
require('@system/maintenance/resources/app');
require('@system/planning/resources/app');
require('@system/research/resources/app');
require('@system/shopping/resources/app');
require('@system/greenhouse/resources/app');

Spork.routesFor('authentication', [
    Spork.authenticatedRoute('settings', './routes/Profile/UserAccount'),
]);