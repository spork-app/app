Spork.setupStore({
    News: require("./store").default,
})

Spork.component('news', require('./News').default);