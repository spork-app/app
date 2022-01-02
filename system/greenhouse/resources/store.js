export default {
    state: {
        plants: [],
        plantPaginator: {},
        seeds: [],
        seedPaginator: {},
        errors: {},
        actions: [],
    },
    getters: {
        greenhouseErrors: state => state.errors,
        plants: state => state.plants,
        seeds: state => state.seeds,
    },
    actions: {
        async getPlants({ state }) {
            const { data: { data, ...pagination } } = await axios.get('/api/greenhouse/plants');
            
            state.plants = data;
            state.plantPaginator = pagination;
        },
        async getSeeds({ state }) {
            const { data: { data, ...pagination } } = await axios.get('/api/greenhouse/seeds');

            state.seeds = data;
            state.seedPaginator = pagination;
        },
        async createPlant({ state }, plant) {
            const { data } = await axios.post('/api/greenhouse/plants', plant);
            state.plants.push(data);
        },
        async createSeeds({ state }, seed) {
            const { data } = await axios.post('/api/greenhouse/seeds', seed);
            state.seeds.push(data);
        },
        async deletePlant({ state }, plant) {
            await axios.delete(`/api/greenhouse/plants/${plant.id}`);

            state.plants = state.plants.filter(p => p.id !== plant.id);
        },
        async deleteSeed({ state }, seed) {
            await axios.delete(`/api/greenhouse/seeds/${seed.id}`);

            state.seeds = state.seeds.filter(p => p.id !== seed.id);
        },
        async updatePlant({ state }, plant) {
            const { data } = await axios.put(`/api/greenhouse/plants/${plant.id}`, plant);

            state.plants = state.plants.map(p => p.id === data.id ? data : p);
        },
        async updateSeed({ state }, seed) {
            const { data } = await axios.put(`/api/greenhouse/seeds/${seed.id}`, seed);

            state.seeds = state.seeds.map(p => p.id === data.id ? data : p);
        },
    }
}