Spork.setupStore({
    Seeds: require("./store").default,
})

Spork.component('date-picker', require('./DatePicker').default);

Spork.routesFor('greenhouse', [
    Spork.authenticatedRoute('/greenhouse', require('./Greenhouse/Seeds').default, {
        children: [
            Spork.authenticatedRoute('', require('./Greenhouse/Dashboard').default),
            Spork.authenticatedRoute('seeds', require('./Greenhouse/SeedsCrud').default),

            Spork.authenticatedRoute('plants', require('./Greenhouse/PlantsCrud').default),
        ]
    }),
]);

Spork.build(async ({ store, router, app }) => {
    if (!store.getters.isAuthenticated) {
        return;
    }

    await store.dispatch('getPlants');
    await store.dispatch('getSeeds');
})