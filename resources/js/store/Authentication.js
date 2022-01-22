import SporkApp from "../SporkApp";

export default {
    state: {
        user: null,
        authenticated: false,
        errors: null,
        loading: false,
    },
    getters: {
        user: state => state.user,
        isAuthenticated: state => state.authenticated && state.user,
    },
    mutations: {
        setAuthenticated(state, isAuthenticated) {
            state.authenticated = isAuthenticated;
        },
        setUser(state, user) {
            state.user = user;
        },
        clearAuth(state) {
            state.authenticated = null;
            state.user = null;
        },
        setErrors(state, errors) {
            state.errors = errors;
        },
    },
    actions: {
        async login({ commit, dispatch, state }, { email, password }) {
            state.loading = true;
            try {
                await axios.post('/login', { email, password })
                commit('setAuthenticated', true);
                await dispatch('fetchUser');
                Spork.bootCallbacks();
                commit('setErrors', null)
            } catch (error) {
                if (error.response.status === 422) {
                     commit('setErrors', error.response.data);
                } else if (error.response.status === 429) {
                    console.log(error.response.headers['x-ratelimit-reset'])
                    commit('setErrors', {
                        email: ['Too many login attempts. Please try again in ' + dayjs(Number(error.response.headers['x-ratelimit-reset']) * 1000).diff(dayjs(), 'second') + ' seconds.'],
                    });
                }
            } finally {
                setTimeout(() => state.loading = false, 400);
            }
        },
        async fetchUser({ commit }) {
            try {
                // use axios to get the user from the api
                const { data } = await axios.get('/api/user');
                commit('setUser', data);
                commit('setAuthenticated', true);
            } catch (error) {
                if (error.response.status === 401) {
                    commit('clearAuth');
                }
            }
        },
        async logout({ commit }) {
            // use axios to logout
            await axios.post('/logout');
            commit('clearAuth');
        }
    },
};
