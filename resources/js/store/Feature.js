export default {
    state: {
        features: Spork.getLocalStorage('feature_data', []),
        pagination: Spork.getLocalStorage('feature_pagination', {}),
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
            'greenhouse',
        ],
        loading: true,
        errors: {},
        open: false,
        queryOptions: {},
    },
    mutations: {
        setOpenResearch(state, value) {
            state.open = value;
        },
        setFeatureLists(state, { data, ...pagination }) {
            state.features = Spork.setLocalStorage('feature_data', data).map(feature => {
                // Here we can modify the features availble with JS objects if needed.
                if (feature.repeatable) {
                    feature.repeatable = feature.repeatable.map(e => new CalendarEvent(e))
                }
                return feature;
            });
            state.pagination = Spork.setLocalStorage('feature_pagination', pagination);
        }
    },
    getters: {
        featuresLoading: state => state.loading,
        featuresPagination: state => state.pagination,
        openResearch: state => state.open,
        features: state => state.features.reduce((allFeatures, feature) => ({
            ...allFeatures,
            [feature.feature]: [ ...new Set([ ...(allFeatures[feature.feature] ? allFeatures[feature.feature]: [] ), feature])],
        }), {}),
        featureErrors: state => state.errors,
        actionsForFeature: state => Object.values(Actions).flat(1).reduce((allActions, action) => ({
            ...action.tags.reduce((tags, tag) => ({
                ...tags,
                [tag]: [ ...new Set([ ...(tags[tag] ? tags[tag]: [] ), action])],
            }), allActions)
        }), {})
    },
    actions: {
        async getFeatureLists({ commit, state }, { filter, feature, ...options }) {
            state.loading = true;
            state.queryOptions = {
                filter, feature, ...options,
            }
            const { data } = await axios.get(buildUrl('/api/feature-list', {
                filter: {
                    ...filter,
                    ...(feature ? { feature } : { }),
                },
                ...options,
                action: 'simplePaginate:100'
            }));

            commit('setFeatureLists', data);
            setTimeout(()=> state.loading = false, 500);
        },
        async createFeature({ commit, state, dispatch }, feature) {
            try { 
                state.loading = true;
                const { data } = await axios.post('/api/feature-list', feature);
                state.features.push(data);
                commit('setOpenResearch', false);

                dispatch('getFeatureLists', state.queryOptions);
            } catch (error) {
                state.errors = error.response.data.errors;
            } finally {
                state.loading = false;
            }
        },
        async updateFeature({ commit, state, dispatch }, feature) {
            try { 
                state.loading = true;
                const { data } = await axios.put('/api/feature-list/'+feature.id, feature);
                state.features = state.features.map(feature => {
                    if (feature.id === data.id) {
                        return {
                            ...feature,
                            ...data,
                        };
                    }

                    return feature;
                });

                dispatch('getFeatureLists', state.queryOptions);
            } catch (error) {
                state.errors = error.response.data.errors;
            }
        },
        async deleteFeature({ commit, state, dispatch }, feature) {
            try { 
                state.loading = true;
                await axios.delete('/api/feature-list/'+feature.id);
                state.features = state.features.filter(feature => feature.id !== feature.id);

                dispatch('getFeatureLists', state.queryOptions);
            } catch (error) {
                state.errors = error.response.data.errors;
            }
        },
        async shareFeature({ commit, state, dispatch }, { feature, email }) {
            try { 
                state.loading = true;
                await axios.post('/api/feature-list/'+feature.id+'/share', { email });
            } catch (error) {
                state.errors = error.response.data.errors;
            } finally {
                state.loading = false;
            }
        },
        async executeAction({ state }, { url, data }) {
            // actionToRun,
            // selectedItems,
            await axios.post(url, data);

        }
    },
    
};
