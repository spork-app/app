import { createRouter, createWebHistory } from "vue-router";
import { createStore } from "vuex";

const route = (path, pathToComponent, extraOptions = {}) => {
    let routeComponent = null
    if (typeof pathToComponent === "string") {
        routeComponent = require(pathToComponent+ "").default;
    } else {
        routeComponent = pathToComponent;
    }

    return Object.assign({
        path,
        component: routeComponent,
        props: true,
    }, Object.assign(extraOptions, {
        meta: Object.assign({}, (extraOptions.meta || {}))
    }));
}

export default class SporkApp {
    constructor(app) { 
        this.app = app;
        this.routes = [];
        this.stores = [];
        this.store = null;
        this.router = null;
        this.callbacks = [];
    }

    async init() {
        this.store = createStore({
            modules: this.stores,
        });

        this.router = createRouter({
            history: createWebHistory(),
            routes: [
                this.unauthenticatedRoute('/login', require('./routes/Auth/Login').default),
                this.unauthenticatedRoute('/register', require('./routes/Auth/Register').default),
                this.unauthenticatedRoute('/forgot-password', require('./routes/Auth/ForgotPassword').default),
                this.authenticatedRoute('/', './routes/Base', {
                    // All routes for Spork, go under the base route.
                    children: [
                        ...this.routes,
                        this.authenticatedRoute('/:catchAll(.*)', require('./routes/404Error').default),
                    ]
                }),
            ]
        });

        // Setup the router
        this.app.use(this.router)

        // Setup the store
        this.app.use(this.store);

        await this.bootCallbacks();

        this.app.mount('#app');
    }

    async bootCallbacks() {
        // Wait for the callbacks to finish (essentially data core to the app, authentication, users, features, etc)
        // Callbacks should be booted on page load, and after authentication.
        for (let callback of this.callbacks) {
            await callback({
                app: this.app,
                store: this.store,
                router: this.router,
            });
        }

    }

    component(tag, component) {
        this.app.component(tag, component);
    }

    setupStore(store) {
        this.stores = {
            ...this.stores,
            ...store,
        }
    }

    routesFor(feature, routes) {
        this.routes.push(...routes);
    }

    build(callback) {
        this.callbacks.push(callback);
    }

    // All routes are added under the base route provided by the app, and are provided
    // a base route 404.
    unauthenticatedRoute(path, pathToComponent, extraOptions = {}) {
        return route(path, pathToComponent, Object.assign({
            meta: {
                forceAuth: false,
            }
        }, extraOptions))
    }

    authenticatedRoute (path, pathToComponent, extraOptions = {}) {
        return route(path, pathToComponent, Object.assign({
            meta: {
                forceAuth: true,
            }
        }, extraOptions))
    }

    toast(message, type = 'success') {
        this.app.$toast[type](message);
        if (type === 'success') { 
            this.sound('finished')
        }

        if (type === 'error') { 
            this.sound('error')
        }
    }

    getLocalStorage(key, defaultValue) {
        return JSON.parse(localStorage.getItem(key)) || defaultValue;
    }

    setLocalStorage(key, value){
        localStorage.setItem(key, JSON.stringify(value));

        return value;
    }

    setBasePath(basePath) {
        this.base_path = basePath;
    }

    basePath(relativePath) {
        return this.base_path + '/' + relativePath;
    }

    sound(name) {
        // glitch-sound
        // error-sound
        // success-sound
        // notification-sound
        const v = document.getElementById(name+'-sound');

        console.log('soudn', v, name);
        v.play();
    }
}