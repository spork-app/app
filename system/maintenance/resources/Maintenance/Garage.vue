<template>
    <div class="w-full">
        <crud-view
            :form="form"
            title="Garage"
            singular="Vehicle"
            @save="save"
            @destroy="onDelete"
            @index="() => $store.dispatch('fetchVehicles')"
            @execute="onExecute"
            :data="$store.getters.vehicles"
        >
            <template v-slot:data="{ data }">
                <div class="flex flex-col">
                    <div class="text-lg text-left">
                        {{ data.year }}
                        {{ data.make }}
                        {{ data.model }}
                    </div>
                    <div class="text-xs">
                        {{ data.vin }}
                    </div>
                </div>
            </template>
            <template v-slot:modal-title>Add to your garage</template>
            <template v-slot:form>
                <div class="flex flex-col">
                    <div class="mt-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="property">
                            Name of your Vehicle
                        </label>
                        <input v-model="form.name" :class="{'border-red-500': form.hasErrors('name') }" class="appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" placeholder="Buggy"/>
                        <div v-if="form.hasErrors('name')" class="w-full text-red-500 text-sm italic">
                            {{ form.error('name')}}
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="property">
                            VIN
                        </label>
                        <input v-model="form.vin" :class="{'border-red-500': form.hasErrors('vin') }" class="appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="property" type="text" placeholder="1FAFP52...">
                        <div v-if="form.hasErrors('vin')" class="w-full text-red-500 text-sm italic">
                            {{ form.error('vin')}}
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="property">
                            Vehicle year:
                        </label>
                        <select v-model="form.model_year" :class="{'border-red-500': form.hasErrors('model_year') }" class="appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="year" type="text" placeholder="2019">
                            <option v-for="year in years" :key="'year'+year" :value="year">{{ year }}</option>
                        </select>
                        <div v-if="form.hasErrors('model_year')" class="w-full text-red-500 text-sm italic">
                            {{ form.error('model_year')}}
                        </div>
                    </div>
                </div>
            </template>

            <template #no-data>No vehicles in your garage</template>
        </crud-view>

    </div>
</template>

<script>
import { ref } from 'vue';

export default {
    setup() {
        return {
            paginator: ref({}),
            vehicles: ref([]),
            page: ref(1),
            createOpen: ref(false),
            form: ref(new Form({
                vin: '',
                model_year: (new Date).getFullYear(),
            })),
            years: Array(200).fill(1, 0, 200).map((i, year) => year + 1900),
            date: ref(new Date()),
            decoded: ref({}),
        }
    },
    watch: {
        date(to, from) {
            this.form.remind_at = dayjs(to).startOf('day').utc().format("YYYY-MM-DD HH:mm:ss")
        }
    },
    methods: {
        hasErrors(error) {
            if (!this.form.errors) {
                return '';
            }

            return this.form.errors[error] ?? null;
        },
        dateFormat(vehicle) {
            return '<span class="text-gray-900">' + vehicle.starts_at  + '  at </span>' +
                '<span class="text-gray-800">' + dayjs(vehicle.last_occurrence || vehicle.remind_at).format('h:mma') + '</span>'
        },
        async save(form) {
            if (!form.id) {
                this.$store.dispatch('createVehicle', form)
            } else {
                console.log('No edit method defined')
            }
        },
        async onDelete(data) {
            await this.$store.dispatch('deleteVehicle', data);
            Spork.toast('Deleted ' + data.name);
        },
        async onExecute({ actionToRun, selectedItems}) {
            try {
                await this.$store.dispatch('executeAction', {
                    url: actionToRun.url,
                    data: {
                        selectedItems
                    },
                });
                Spork.toast('Success! Running action...');

            } catch (e) {
                Spork.toast(e.message, 'error');
            }
        },
    },
    mounted() {
    }
}
</script>

<style scoped>

</style>
