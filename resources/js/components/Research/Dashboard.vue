<template>
    <div class="flex flex-wrap gap-4 m-4">
        <button @click="() => open = !open">Begin new research</button>
        <div class="w-full font-medium text-gray-600 uppercase ml-3">Recent</div>
        <router-link :to="'/research/'+ topic.id" v-for="(topic, i) in $store.getters.topics" class="p-3 border border-gray-200 rounded-lg bg-white" :key="i">
            <div  class="font-medium">{{ topic.name }}</div>
            <pre class="w-64 h-48 shadow-inset overflow-hidden text-xs border-t py-2 my-2">{{ topic.settings.body }}</pre>
            <div class="text-gray-500 border-t mt-4 pt-2">{{ date(topic.updated_at) }}</div>
        </router-link>

        <!-- This example requires Tailwind CSS v2.0+ -->
        <div v-if="open" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
                <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div>
                    <div class="text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Research Name
                        </h3>
                        <div class="mt-2">
                            <input v-model="form.name" placeholder="Topic of study..." type="text" class="block max-w-lg w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md"/>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button 
                        @click="createFeature"
                        type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm"
                    >
                        Start New Research
                    </button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                        Cancel
                    </button>
                </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: "Dashboard.vue",
    data() {
        return {
            open: false,
            form: {
                name: '',
                settings: {
                    body: '',
                    links: [],
                },
                feature: 'research' ,
            },
        }
    },
    methods: {
        date(date) {
            return dayjs(date).format('lll')
        },
        openModal() {
            this.open = true;
        },
        createFeature() {
            this.$store.dispatch('createFeature', this.form)
            this.open = false;
        },
    }
}
</script>
