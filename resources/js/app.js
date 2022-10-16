import './bootstrap';

Spork.setupStore({
    ActivityLog: require('./store/ActivityLog').default,
})

Spork.build(async ({ store, router }) => {
    await axios.get('/sanctum/csrf-cookie');
    await store.dispatch('fetchUser');
    if (!store.getters.isAuthenticated && router.currentRoute.path !== '/login') {
        router.push('/login');
        return;
    }

    const fetchFeatures = () => store.dispatch('fetchFeatures');

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

require('@system/core/resources/app');

// Shopping needs to be refactored, the current service (meijer) isn't fully supported
// require('@system/shopping/resources/app');
require('@system/greenhouse/resources/app');
require('@system/food/resources/app');

Spork.routesFor('base', [
    Spork.authenticatedRoute('/', require('./routes/Dashboard').default),
    Spork.authenticatedRoute('settings', require('./routes/Profile/UserAccount').default),
]);
