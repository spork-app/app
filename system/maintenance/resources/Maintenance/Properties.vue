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
                            <label for="street-address" class="block text-sm font-medium text-gray-700">Street address</label>
                            <input type="text" name="state" id="state" class="mt-1 py-2 px-4 border focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
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
                vin: '',
                model_year: 2019
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