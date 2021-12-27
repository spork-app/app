
Spork.build(async ({ app }) => {
    console.log(app.config.globalProperties.$store.getters.isAuthenticated);
    if (!app.config.globalProperties.$store.getters.isAuthenticated) {
        app.config.globalProperties.$router.push('/login');
    }
})

Spork.init()
