
Spork.init([
    
        Spork.unauthenticatedRoute('/login', require('./routes/Auth/Login').default),
        Spork.unauthenticatedRoute('/register', require('./routes/Auth/Register').default),
        Spork.unauthenticatedRoute('/forgot-password', require('./routes/Auth/ForgotPassword').default),
        Spork.authenticatedRoute('/', require('@routes/Base').default, {
            // All routes for Spork, go under the base route.
            children: [
                ...(Spork.routes ?? []),
                Spork.authenticatedRoute('/:catchAll(.*)', require('./routes/404Error').default),
            ]
        }),
    
]).then(() => {
    console.log('[-] Spork has been initialized!')
}).catch((error) => {
    console.error('[-] Spork has failed to initialize!', error)
})
