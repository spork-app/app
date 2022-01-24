<template>
    <div class="w-full">
        <crud-view
            :form="form"
            title="Seeds"
            singular="Seed"
            @save="onSave"
            @destroy="onDelete"
            @index="() => $store.dispatch('getSeeds')"
            @execute="onExecute"
            :data="$store.getters.seeds"

        >
            <template v-slot:data="{ data }">
                <div class="flex flex-col">
                    <div class="text-lg text-left">
                        {{ data.name }}
                    </div>
                    <div class="text-xs">
                        {{ $store.getters.plants?.filter(plant => plant.id == data.plant_id)[0]?.name }}
                    </div>
                </div>
            </template>
            <template #modal-title>Add to your greenhouse</template>
            <template #form>
                <div class="flex flex-col gap-4 mt-2">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6">
                            <label for="seed_name" class="block max-w-lg text-sm font-medium text-gray-700 dark:text-gray-200">Name</label>
                            <spork-input v-model="form.name" type="text" name="seed_name" id="seed_name" />
                        </div>
                    </div>

                    <div class="my-4">
                        <div class="text-left">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200" for="plantselector">
                                Parent Plant
                            </label>
                            <div class="mt-2">
                                <select v-model="form.plant_id" class="block max-w-lg w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 dark:border-gray-500 dark:bg-gray-500 rounded-md ">
                                    <option v-for="plant in $store.getters.plants" :key="plant.id" :value="plant.id">{{ plant.name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="text-gray-700">
                        <date-picker :modelValue="form.germinated_at" @update:modelValue="value => form.germinated_at = value" label="Germinated at"/>
                    </div>

                    <div class="text-gray-700">
                        <date-picker :modelValue="form.planted_at" @update:modelValue="value => form.planted_at = value" label="Planted at"/>
                    </div>

                    <div class="text-gray-700">
                        <date-picker :modelValue="form.expected_harvest_date" @update:modelValue="value => form.expected_harvest_date = value" label="Expected harvest date"/>
                    </div>

                    <div class="text-gray-700">
                        <date-picker :modelValue="form.harvested_at" @update:modelValue="value => form.harvested_at = value" label="Expected harvest date"/>
                    </div>

                    <div class="text-gray-700">
                        <date-picker :modelValue="form.last_watered_at" @update:modelValue="value => form.last_watered_at = value" label="Expected harvest date"/>
                    </div>

                    <div class="text-gray-700">
                        <date-picker :modelValue="form.last_fed_at" @update:modelValue="value => form.last_fed_at = value" label="Expected harvest date"/>
                    </div>
                </div>
            </template>

            <template #no-data>No seeds in your greenhouse</template>
        </crud-view>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: new Form({
                plant_id: null,
                germinated_at: null,
                planted_at: null,
                expected_harvest_at: null,
                harvested_at: null,
                last_watered_at: null,
                last_fed_at: null,
            }),
            date: null,
        }
    },
    methods: {
        hasErrors(error) {
            if (!this.form.errors) {
                return '';
            }

            return this.form.errors[error];
        },
        async onSave(data) {
            if (!this.form.id) {
                await this.$store.dispatch('createSeeds', this.form);
                Spork.toast('Created ' + data.name);

            } else {
                await this.$store.dispatch('updateSeed', this.form);
                Spork.toast('Updated ' + data.name);
            }
        },
        async onDelete(data) {
            await this.$store.dispatch('deleteSeed', data);
            Spork.toast('Deleted ' + data.name);
        },
        async onExecute({ actionToRun, selectedItems}) {
            try {
                await this.$store.dispatch('executeAction', {
                    url: actionToRun.url,
                    data: {
                        plants: selectedItems.map(item => item.id)
                    },
                });
                Spork.toast('Success! Running action...');

            } catch (e) {
                Spork.toast(e.message, 'error');
            }
        },
    }
}
</script>

<style scoped>

</style>
