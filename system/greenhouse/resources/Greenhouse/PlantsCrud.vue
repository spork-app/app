<template>
    <div class="w-full">
        <crud-view
            :form="form"
            title="Plants"
            singular="Plant"
            @save="onSave"
            @destroy="onDelete"
            @index="() => $store.dispatch('getPlants')"
            @execute="onExecute"
            :data="$store.getters.plants"
        >
            <template v-slot:data="{ data }">
                <div class="flex flex-col">
                    <div class="text-lg text-left">
                        {{ data.name }}
                    </div>
                    <div class="text-xs">
                        Expected yield: {{ data.expected_yield_per_harvest }}
                    </div>
                </div>
            </template>
            <template #modal-title>Add to your greenhouse</template>
            <template #form>
                <div class="flex flex-col gap-4 mt-2">
                    <div class="grid grid-cols-6 gap-6 mt-2">
                        <div class="col-span-6">
                            <label for="plant_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Name</label>
                            <spork-input v-model="form.name" type="text" name="plant_name" id="plant_name" />
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-6 mt-2">
                        <div class="col-span-6">
                            <label for="time_to_harvest" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Time to harvest (in days)</label>
                            <spork-input v-model="form.time_to_harvest" type="number" min="0" max="365" name="time_to_harvest" id="time_to_harvest"/>
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-6 mt-2">
                        <div class="col-span-6">
                            <label for="expected_yield_per_harvest" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Expected Yield (in grams)</label>
                            <spork-input v-model="form.expected_yield_per_harvest" type="number" min="0" max="365" name="expected_yield_per_harvest" id="time_to_harvest" />
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-6 mt-2">
                        <div class="col-span-6">
                            <label for="is_perpetual_harvest" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Can this be harvested perpetually?</label>
                            <input v-model="form.is_perpetual_harvest" type="checkbox" name="is_perpetual_harvest" id="is_perpetual_harvest" class=" border focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md" />
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-6 gap-6 mt-2">
                        <div class="col-span-6">
                            <label for="nutrient_schedule" class="block text-sm font-medium text-gray-700">Nutrient Schedule</label>
                            <textarea  name="nutrient_schedule" id="nutrient_schedule" class="h-24 mt-1 py-2 px-4 border focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-6 mt-2">
                        <div class="col-span-6">
                            <label for="settings" class="block text-sm font-medium text-gray-700">Settings</label>
                            <textarea name="settings" id="settings" class="h-24 mt-1 py-2 px-4 border focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                        </div>
                    </div>
                </div>
            </template>

            <template #no-data>No plants in your greenhouse</template>
        </crud-view>
    </div>
</template>

<script>
import { ref } from 'vue';
export default {
    setup() {
        return {
            paginator: ref({}),
            properties: ref([]),
            page: ref(1),
            createOpen: ref(false),
            form: ref(new Form({
                name: '',
                time_to_harvest: 0,
                expected_yield_per_harvest: 0,
                is_perpetual_harvest: false,
                nutrient_schedule: {},
                settings: {},
            })),
            years: [],
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

            return this.form.errors[error];
        },
        onSave(data) {
            if (!this.form.id) {
                this.$store.dispatch('createPlant', this.form);
            } else {
                this.$store.dispatch('updatePlant', this.form);
            }
        },
        onDelete(data) {
            console.log(data)
            this.$store.dispatch('deletePlant', data);
        },
        async onExecute() {
            try {
                await this.$store.dispatch('executeAction', {
                    url: actionToRun.url,
                    data: {
                    },
                });
                Spork.toast('Success! Running action...');

            } catch (e) {
                Spork.toast(e.message, 'error');
            }
        }
    }
}
</script>

<style scoped>

</style>
