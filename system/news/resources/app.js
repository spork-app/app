Spork.setupStore({
    News: require("./store").default,
})

Spork.routesFor('news', [
    Spork.authenticatedRoute('/news', require('./News').default),
]);