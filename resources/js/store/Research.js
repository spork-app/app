export default {
    state: {
        topics: [],
        research: [],
        researchLoading: false,
    },
    getters: {
        topics: (state) => state.topics,
        research: (state) => state.research,
        researchLoading: state => state.researchLoading,
    },
    mutations: {
        setResearch(state, value) {
            state.research = value;
        },
        setResearchLoading(state, value) {
            state.researchLoading = value;
        }
    },
    actions: {
        async getResearches({ state }) {
            const { data: { data } } = await axios.get(buildUrl('/api/feature-list', {
                filter: {
                    feature: 'research',
                },
                include: 'notes'
            }));

            state.topics = data;
        },
        async updateResearch({ state }, featureList) {
            const { data } = await axios.put(buildUrl('/api/feature-list/'+featureList.id), featureList);

            state.topics = state.topics.map(topic => {
                if (topic.id !== featureList.id) {
                    return topic;
                }

                return data;
            });
        },
        async search({ state, commit }, { search, url }) {
            commit('setResearchLoading', true);
            const { data } = await axios.get(url ?? buildUrl('/api/research', {
                q: search,
            }));

            commit('setResearch', data);
            commit('setResearchLoading', false);
        },
    },
};

