Spork.setupStore({
    Food: require("./store").default,
})

Spork.routesFor('food', [
    Spork.authenticatedRoute('/food', require('./Food/Food').default),
    Spork.authenticatedRoute('/food/recipes/:slug', require('./Food/SingleRecipe').default),
]);