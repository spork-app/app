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

// Register spork plugins here. I haven't figured out a great way to auto-discover and also optionally disable things yet.
// require('@spork/development/resources/app')

Spork.routesFor('base', [
    Spork.authenticatedRoute('/', require('./routes/Dashboard').default),
    Spork.authenticatedRoute('settings', require('./routes/Profile/UserAccount').default),
]);
