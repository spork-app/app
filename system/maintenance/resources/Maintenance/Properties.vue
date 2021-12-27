<template>
    <div class="w-full">
        <crud-view
            :form="form"
            title="Properties"
            singular="Property"
            api-url="/api/properties"
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
        }
    },
    mounted() {
        // Echo.private('property')
        //     .listen('.property.triggered', ({ property }) => {
        //         // Let's check if the browser supports notifications
        //         if (!("Notification" in window)) {
        //             alert("This browser does not support desktop notification");
        //         }
        //
        //         // Let's check whether notification permissions have already been granted
        //         else if (Notification.permission === "granted") {
        //             // If it's okay let's create a notification
        //             var notification = new Notification(property.name, {
        //                 body: property.name
        //             });
        //
        //             notification.onclick = function() {
        //                 window.open('https://life.test/properties');
        //             };
        //         }
        //
        //         // Otherwise, we need to ask the user for permission
        //         else if (Notification.permission !== "denied") {
        //             Notification.requestPermission().then(function (permission) {
        //                 // If the user accepts, let's create a notification
        //                 if (permission === "granted") {
        //                     var notification = new Notification(property.name);
        //                     notification.onclick = function() {
        //                         window.open('https://life.test/properties');
        //                     };
        //                 }
        //             });
        //         }
        //     });
    }
}
</script>

<style scoped>

</style>
