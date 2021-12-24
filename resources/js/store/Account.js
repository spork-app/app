export default {
    state: {
        accounts: [],
        pagination: {},
        loading: false,
        errors: null,
    },
    actions: {
        async createAccount({ state }, account) {
            state.loading = true;
            try {
                await axios.post('/api/account', account);
            } catch (e) {
                if (e.response.status === 422) {
                    state.errors = e.response.data
                }
            } finally {
                setTimeout(()=> state.loading = false, 500);
            }
        }
    },
};

