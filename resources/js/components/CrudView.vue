<template>
    <div class="w-full mt-4 ">
        <div class="mx-4">
            <div class="container mx-auto">
                <div class="flex flex-wrap ">
                    <div class="text-4xl font-medium text-blue-900">
                        {{ title }}
                    </div>

                    <div class="w-full mt-4 flex flex-wrap items-center justify-between">
                        <div class="relative flex-1 max-w-2xl text-gray-700">
                            <input type="text" class="bg-white w-full pl-12 py-2 rounded-full border-gray-300 min-w-4xl" placeholder="Search..." />
                            <div class="absolute top-0 left-0 ml-3 mt-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                        </div>
                        <div class="text-right ml-4">
                            <button @click="createOpen = true" class="bg-blue-400 font-bold tracking-wide text-white rounded px-6 py-2 shadow">
                                Create {{singular}}
                            </button>
                        </div>
                    </div>

                    <div class="w-full bg-white shadow rounded mt-4 flex flex-wrap items-center justify-between">
                        <div class="bg-gray-200 relative border-b border-gray-300 w-full p-4 flex flex-wrap justify-between items-center">
                            <div>
                                <input @change="selectAll" type="checkbox">
                            </div>
                            <button @click="filtersOpen= !filtersOpen" class="focus:outline-none flex flex-wrap items-center p-2 rounded-lg" :class="{'bg-gray-300': filtersOpen, 'bg-gray-100': !filtersOpen}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div v-if="filtersOpen" class="absolute bg-white shadow-lg top-0 right-0 mt-14 mr-4 border border-gray-200 rounded-lg" style="width: 250px;">
                                <div class="bg-gray-100 uppercase py-2 px-2 font-bold text-gray-500 text-sm">
                                    filters
                                </div>
                                <div class="flex flex-wrap items-center p-2">
                                    <select v-model="itemsPerPage" class="border border-gray-300 rounded-lg w-full p-1">
                                        <option value="15">15 items per page</option>
                                        <option value="30">30 items per page</option>
                                        <option value="100">100 items per page</option>
                                    </select>
                                </div>
                                <div v-if="actions?.length > 0" class="uppercase py-2 px-2 font-bold text-gray-500 text-sm">
                                    actions
                                </div>
                                <div class="flex flex-wrap items-center p-2 gap-2" v-if="actions?.length > 0">
                                    <select v-model="actionToRun" class="border border-gray-300 rounded-lg flex-grow p-1">
                                        <option v-for="action in actions" :key="action" :value="action">{{ action.name }} ({{ selectedItems.length }})</option>
                                    </select>

                                    <button type="button" @click.prevent="$emit('execute', { selectedItems, actionToRun })">
                                        <play-icon class="w-6 h-6 stroke-current" />
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div v-if="data.length > 0" class="w-full flex flex-wrap rounded-b ">
                            <div v-for="(datum, $i) in data" :key="'crud-view'+$i" class="w-full py-4 px-2 flex flex-wrap items-center border-b">
                                <div class="w-6 mx-2">
                                    <input type="checkbox" v-model="selectedItems" :value="datum">
                                </div>
                                <div class="flex-1">
                                    <slot v-if="datum" class="flex-1" :data="datum" name="data">
                                        <pre class="flex-1">fallback: {{ datum}}</pre>
                                    </slot>
                                </div>
                                <div class="flex items-center w-8">
                                    <button type="button" @click.prevent="$emit('destroy', datum)">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div v-else class="w-full p-4 italic text-center">
                            No {{ singular.toLowerCase() }} data
                        </div>

                    </div>
                </div>
            </div>
            <div v-if="createOpen" class="fixed z-0 inset-0 flex items-center outline-none w-screen h-screen overflow-y-scroll">
                <div class="relative z-10 w-full md:w-1/2 mx-auto max-h-screen overflow-y-auto">
                    <div class="w-full rounded p-4 bg-white shadow-lg text-left">
                        <div class="text-xl flex justify-between">
                            <slot name="modal-title">Create Modal</slot>
                            <button @click="createOpen = false" class="focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <div class="flex flex-col border-t border-gray-200 mt-2">
                            <slot name="form"></slot>
                            <div class="mt-4 flex justify-end">
                                <button @click.prevent="() => {
                                    $emit('save', form);
                                    createOpen = false;
                                    }" class="border border-blue-500 py-2 px-4 rounded text-blue-500 hover:bg-blue-500 hover:text-white">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div @click="createOpen = false" v-if="createOpen" class="fixed z-0 cursor-pointer inset-0 flex items-center outline-none" style="background: rgba(0,0,0,0.4);"></div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';
import { PlayIcon } from "@heroicons/vue/outline";

export default {
    components: {
        PlayIcon
    },
    props: {
        form: {
            type: Object,
            default: () => new Form({}),
        },
        title: {
            type: String,
            default: 'Title',
        },
        singular: {
            type: String,
            default: 'singular',
        },

        // store
        save: {
            type: Function,
            default: (item) => {},
        },
        destroy: {
            type: Function,
            default: (item) => {},
        },
        index: {
            type: Function,
            default: (params) => {},
        },

        // getters
        data: {
            type: Array,
            default: () => [],
        },
    },
    setup() {
        return {
            createOpen: ref(false),
            filtersOpen: ref(false),
            selectedItems: ref([]),
            itemsPerPage: ref(15),
            actionToRun: ref(null),
        }
    },
    computed: {
        actions() {
            const key = this.title.toLowerCase().replace(' ', '-');

            return this.$store.getters.actionsForFeature[key] ?? []
        }
    },
    methods: {
        hasErrors(error) {
            if (!this.form.errors) {
                return '';
            }

            return this.form.errors[error];
        },
        getData() {
            this.$emit('index');
        },
        selectAll(event) {
            if (event.target.checked) {
                this.selectedItems = this.data;
            } else {
                this.selectedItems = [];
            }
        },
    },
    mounted() {
        this.getData()
    }
}
</script>

<style scoped>

</style>
