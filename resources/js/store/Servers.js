
export default {
    state: {
        servers: []
    },
    getters: {
        servers: (state) => state.servers,
    },
    mutations: {
        establishServers(state, servers) {
            state.servers = servers;
        }
    },
    actions: {
        async fetchServers({ commit, state }) {
            const { data } = await axios.get(process.env.MIX_SERVER_SERVER, {
                withCredentials: false
            });

            commit('establishServers', data);
        }
    }
}