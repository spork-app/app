Spork.setupStore({
    Seeds: require("./store").default,
})

Spork.routesFor('seeds', [
    Spork.authenticatedRoute('/seeds', require('./Seeds/Seeds').default, {
        children: [
            Spork.authenticatedRoute('', require('./Seeds/Dashboard').default),
            Spork.authenticatedRoute('crud', require('./Seeds/Crud').default),
            Spork.authenticatedRoute('/:seedId', require('./Seeds/Seed').default),
        ]
    }),
    
    Spork.authenticatedRoute('/plants', require('./Seeds/Seeds').default, {
        children: [
            Spork.authenticatedRoute('crud', require('./Seeds/Plants').default),
        ]
    }),
]);