<template>
    <div>
        <div v-if="!actualFeature || (actualFeature.length > 0 && allowMoreThanOne !== undefined)">
            <button @click="$store.commit('setOpenResearch', true)" class="border border-blue-500 dark:border-blue-600 dark:bg-blue-600 dark:text-blue-100 py-1 px-2 text-sm hover:underline rounded text-blue-500">Add {{ feature }}</button>
        </div>
        <div v-if="$store.getters.openResearch" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900 opacity-75 transition-opacity" aria-hidden="true"></div>

                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
                <div class="inline-block align-bottom bg-white dark:bg-gray-600 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div>
                        <div  v-if="feature === 'research'">
                            <div class="text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-50" id="modal-title">
                                    Research Topic
                                </h3>
                                <div class="mt-2">
                                    <spork-input v-model="form.name" placeholder="Topic of study..." type="text"/>
                                </div>
                            </div>    
                        </div>

                        <div v-if="feature === 'budget'">
                            <div class="">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Name
                                </h3>
                                <div class="mt-2">
                                    <input v-model="modelValue.name" placeholder="Subscriptions" type="text" class="block max-w-lg w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md"/>
                                </div>
                            </div>
                            <div class="mt-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Amount
                                </h3>
                                <div class="mt-2">
                                    <input v-model="modelValue.amount" placeholder="100.50" type="text" class="block max-w-lg w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md"/>
                                </div>
                            </div>
                            <div class="mt-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Groups
                                </h3>
                                <div class="mt-2 p-2 text-sm font-normal flex flex-col gap-2 border border-gray-200 rounded-lg max-h-32 overflow-y-scroll">
                                    <label class="cursor-pointer">
                                        <input v-model="modelValue.groups" type="checkbox" value="1" class="mr-2 rounded" />
                                        Subscription
                                    </label>    
                                    
                                    <label class="cursor-pointer">
                                        <input v-model="modelValue.groups" type="checkbox" value="1" class="mr-2 rounded" />
                                        Utility
                                    </label>
                                    
                                    <label class="cursor-pointer">
                                        <input v-model="modelValue.groups" type="checkbox" value="1" class="mr-2 rounded" />
                                        Loans
                                    </label>
                                    <label class="cursor-pointer">
                                        <input v-model="modelValue.groups" type="checkbox" value="1" class="mr-2 rounded" />
                                        Holiday Spending
                                    </label>
                                    <label>
                                        <input v-model="modelValue.groups" type="checkbox" value="1" class="mr-2 rounded" />
                                        Fast Food
                                    </label>
                                    <label>
                                        <input v-model="modelValue.groups" type="checkbox" value="1" class="mr-2 rounded" />
                                        Hobbies
                                    </label>
                                    <label>
                                        <input v-model="modelValue.groups" type="checkbox" value="1" class="mr-2 rounded" />
                                        Servers
                                    </label>
                                    <label>
                                        <input v-model="modelValue.groups" type="checkbox" value="1" class="mr-2 rounded" />
                                        Other Tech
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div  v-if="feature === 'development'" class="flex flex-col gap-2">
                            <div class="text-left">
                                <label for="country" class="block text-sm font-medium text-gray-700 dark:text-slate-200">Project Name</label>
                                <div class="mt-2">
                                    <spork-input v-model="form.name" placeholder="Greenhouse" type="text"/>
                                </div>
                            </div>    
                            <div class="text-left">
                                <label for="country" class="block text-sm font-medium text-gray-700 dark:text-slate-200">Project Path</label>
                                <div class="mt-2">
                                    <spork-input v-model="form.settings.path" placeholder="/home/john/src/project-name" type="text"/>
                                </div>
                            </div>    

                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 dark:text-slate-200">Template</label>
                                <select v-model="form.settings.template" id="country" name="country" autocomplete="country-name" class="mt-1 block w-full bg-white dark:bg-slate-500 dark:border-gray-500 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option v-for="template in templates" :key="template.src" :value="template">{{template.name}}</option>
                                </select>
                            </div>
                            <div class="text-left mt-2">
                                <label for="use_git" class="flex items-center -ml-4 block text-sm font-medium text-gray-700 dark:text-slate-200">
                                    <spork-input v-model="form.settings.use_git" type="checkbox"/>
                                    <div class="ml-6">Initialize git afterwords</div>
                                </label>
                            </div>    

                        </div>
                    </div>
                    <div v-for="(item, key) in $store.getters.featureErrors" :key="key">
                        <div v-for="error in item" :key="error" class="text-red-500">{{ error  }}</div>
                    </div>
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                        <button 
                            @click="createFeature"
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm"
                        >
                            Create {{ feature }}
                        </button>
                        <button 
                            @click=" $store.commit('setOpenResearch', false)"
                            type="button" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';

export default {
    props: ['feature', 'allowMoreThanOne'],
    computed: {
        actualFeature() {
            return this.$store.getters.features[this.feature]
        },
    },
    methods: {
        createFeature() {
            this.$store.dispatch('createFeature', {
                ...this.form,
                feature: this.feature,
            })
            this.open = false;
        },
    },

    data() {
        return {
            form: {
                name: '',
                settings: {
                    path: '',
                    template: '',
                }
            },

            // Development Feature
            templates: [
                {
                    name: 'Spork Plugin',
                    // If it's a file path that exists, we'll copy from there, if it's a URL or git repo, we'll download it
                    src: 'https://github.com/spork-app/template-plugin/archive/main.zip',
                },
                
                {
                    name: 'Laravel App',
                    // If it's a file path that exists, we'll copy from there, if it's a URL or git repo, we'll download it
                    src: 'https://github.com/laravel/laravel/archive/master.zip',
                },
                
            ]
        }
    },

    setup() {
        return {
            open: ref(false),
        }
    }
}
</script>