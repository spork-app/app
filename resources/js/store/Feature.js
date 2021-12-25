export default {
    state: {
        features: [],
        pagination: {},
        availableFeatures: [
            'research',
            'finance',
            'planning',
            'maintenance',
            'shopping',
            'news',
            'weather',
            'property',
            'calendar',
        ],
        loading: true,
        errors: {},
        open: false,
    },
    mutations: {
        setOpenResearch(state, value) {
            state.open = value;
        }
    },
    getters: {
        openResearch: state => state.open,
        features: state => state.features.reduce((allFeatures, feature) => ({
            ...allFeatures,
            [feature.feature]: [ ...new Set([ ...(allFeatures[feature.feature] ? allFeatures[feature.feature]: [] ), feature])],
        }), {}),
        manualFinance: state => (state.features.reduce((allFeatures, feature) => ({
            ...allFeatures,
            [feature.feature]: [ ...new Set([ ...(allFeatures[feature.feature] ? allFeatures[feature.feature]: [] ), feature])],
        }), {})?.finance || []).filter(account => account.name === 'Manual accounts')[0],

        allAccountsFromFeatures: state => state.features.filter(f => f.feature === 'finance').reduce((accounts, ac) => ([
            ... new Set([
                ...accounts,
                ...ac.accounts,
            ])
        ]), []),
        featureErrors: state => state.errors,
    },
    actions: {
        async getFeatureLists({ commit, state }, { filter, feature, ...options }) {
            state.loading = true;
            const { data: { data }, ...pagination } = await axios.get(buildUrl('/api/feature-list', {
                filter: {
                    ...filter,
                    ...(feature ? { feature } : { }),
                },
                ...options,
            }));

            state.features = data;
            state.pagination = pagination;
            setTimeout(()=> state.loading = false, 500);
        },
        async createFeature({ commit, state }, feature) {
            try { 
                state.loading = true;
                const { data } = await axios.post('/api/feature-list', feature);
                state.features.push(data);
                commit('setOpenResearch', false);
            } catch (error) {
                state.errors = error.response.data.errors;
            } finally {
                state.loading = false;
            }
        }
    },
};
