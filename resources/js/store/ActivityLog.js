export default {
    state: {
        logs: [],
        pagination: {},
    },

    getters: {
        activityLogs: state => state.logs,
    },
    actions: {
        async fetchLogs({ state, commit }, options) {
            const { data: { data, ...pagination } } = await axios.get(buildUrl('/api/activity-logs', options));
            state.logs = data;
            state.pagination = pagination;
        }
    }
}