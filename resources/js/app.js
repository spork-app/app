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
Spork.component('spork-input', require('./components/SporkInput').default);
Spork.component('loading-ascii', require('./components/LoadingAscii').default);


Spork.setupStore({
    Navigation: require('./store/Navigation').default,
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

    const fetchFeatures = () =>  store.dispatch('getFeatureLists', {
        include: ['accounts', 'repeatable.users.user'],
    });

    store.dispatch('fetchLogs', {
        filter: {
            subject_type: 'Spork\\Greenhouse\\Models\\Plant'
        },
        include: 'causer,subject',
    })
    console.log(`user.${store.getters.user.id}`);

    fetchFeatures();
    Echo.private(`user.${store.getters.user.id}`)
        .listen('.FeatureCreated', fetchFeatures)
        .listen('.FeatureDeleted', fetchFeatures)
        .listen('.FeatureUpdated', fetchFeatures)
        .listen('.SetupComplete', (event) => {
            Spork.toast('Setup complete! Enjoy your new Spork!');
        })
        .notification((notification) => {
            store.dispatch('fetchUser');
        })
});

// require('@vendor/spork/development/resources/app');

Spork.routesFor('authentication', [
    Spork.authenticatedRoute('setup', './routes/Setup'),
    Spork.authenticatedRoute('settings', './routes/Profile/UserAccount'),
]);
