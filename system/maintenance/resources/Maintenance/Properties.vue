<template>
    <div class="w-full">
        <crud-view
            :form="form"
            title="Properties"
            singular="Property"
            @save="save"
            @destroy="onDelete"
            @index="() => $store.dispatch('fetchVehicles')"
            @execute="onExecute"
            :data="$store.getters.properties"
        >
            <template v-slot:data="{ data }">
                <div class="flex flex-col">
                    <div class="text-lg text-left">
                        {{ data.name }}
                    </div>
                    <div class="text-xs">
                        {{ data.address }}
                    </div>
                </div>
            </template>
            <template #modal-title>Add to your realestate portfolio</template>
            <template #form>
                <div>
                    <div class="grid grid-cols-6 gap-6 mt-2">
                        <div class="col-span-6">
                            <label for="name" class="block text-sm font-medium">Name</label>
                            <spork-input type="text" name="name" id="name" />
                        </div>
                        <div class="col-span-6">
                            <label for="street-address" class="block text-sm font-medium">Street address</label>
                            <spork-input type="text" name="state" id="state" />
                        </div>

                        <div class="font-bold uppercase col-span-6 text-xs text-gray-600 dark:text-gray-50 tracking-wide">calendar integration</div>
                        <div class="col-span-6 flex items-center mx-4">
                            <div class="flex items-center h-5">
                                <input id="track_air_filter" name="track_air_filter" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" />
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="track_air_filter" class="font-medium">Track airfilter replacements?</label>
                                <p class="text-gray-500 dark:text-gray-300">Get notified every six months from your move-in date, to replace your airfilter.</p>
                            </div>
                        </div>

                        <div class="col-span-6 flex items-center mx-4">
                            <div class="flex items-center h-5">
                                <input id="track_water_filter" name="track_water_filter" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" />
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="track_water_filter" class="font-medium">Track water filter replacements?</label>
                                <p class="text-gray-500 dark:text-gray-300">Get notified every three months from your move-in date, to replace your water filters.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #no-data>No properties in your garage</template>
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
                address: '',
                name: '',
                is_prirmary_address: true,
                track_air_filter: dayjs(),
                track_water_filter: null,
                // track_
            })),
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
        dateFormat(property) {
            return '<span class="text-gray-900">' + property.starts_at  + '  at </span>' +
                '<span class="text-gray-800">' + dayjs(property.last_occurrence || property.remind_at).format('h:mma') + '</span>'
        },
        
        async save(form) {
            if (!form.id) {
                this.$store.dispatch('createProperty', form)
            } else {
                console.log('No edit method defined')
            }
        },
        async onDelete(data) {
            await this.$store.dispatch('deleteProperty', data);
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