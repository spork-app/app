Spork.setupStore({
    Maintenance: require("./store").default,
})

Spork.routesFor('maintenance', [
    Spork.authenticatedRoute('/maintenance/garage', require('./Maintenance/Garage').default),
    Spork.authenticatedRoute('/maintenance/properties', require('./Maintenance/Properties').default),
]);