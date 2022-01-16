export default {
    data: {
        vehicles: [],
    },
    getters: {
        vehicles: state => state.vehicles,
    },
    actions: {
        async fetchVehicles({ state, commit}) {
            const { data: { data } } = await axios.get('/api/vehicles');
            state.vehicles = data;
        },
        async createVehicle({ state, commit }, vehicle) {

            const { data: {
                Make: make,
                Model: model,
                Series: trim,
                ModelYear: model_year,
                VIN: vin,
                FuelTypePrimary: fuel,
                Seats: seats,
                TopSpeedMPH: max_top_speed,
                TransmissionStyle: transmission,
                TransmissionSpeeds: transmission_size,
            } } = await axios.get('https://car.metabit.workers.dev/'+ vehicle.vin + '/' + vehicle.model_year, {
                withCredentials: false,
            })
            
            try { 
                await axios.post(buildUrl('/api/vehicles'), Object.assign({
                    make, model, trim , model_year, vin, fuel, seats, max_top_speed, transmission, transmission_size,
                }, vehicle))
            } catch (e) {
                state.errors = e.response.data.errors;
                return;
            }
        }, 
        async deleteVehicle({ state, commit }, vehicle) {
            await axios.delete(buildUrl('/api/vehicle/' + vehicle.id));
            state.vehicles = state.vehicles.filter(v => v.id !== vehicle.id);
        },
        async deleteProperty({ state, commit }, vehicle) {
            await axios.delete(buildUrl('/api/property/' + vehicle.id));
            state.vehicles = state.vehicles.filter(v => v.id !== vehicle.id);
        },
        async fetchProperties({ state, commit }) {
            const { data: { data } } = await axios.get('/api/properties');
            state.properties = data;
        },
        async createProperty({ state, dispatch }, property) {
            try {
                await axios.post(buildUrl('/api/properties'), property);
                await dispatch('fetchProperties');
            } catch (e) {
                state.errors = e.response.data.errors;
                return;
            }
        }
    }
}