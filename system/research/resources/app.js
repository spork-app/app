Spork.setupStore({
    Research: require("./store").default,
})

Spork.routesFor('research', [
    Spork.authenticatedRoute('/research', require('./Research/Research').default, {
        children: [
            Spork.authenticatedRoute(':id', require('./Research/Topic').default),
            Spork.authenticatedRoute('', require('./Research/Dashboard').default),
        ]
    }),
]);