<template>
    <div class="m-4 rounded shadow overflow-hidden">
       
        <div class="bg-white p-4">
            <label class="block text-sm font-medium text-gray-700">
                {{ label }}
            </label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                <div v-if="!file" class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600">
                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                            <span>Upload a csv</span>
                            <input @change="(input) => file = input" id="file-upload" type="file" class="sr-only" />
                        </label>
                        <p class="pl-1">or drag and drop</p>
                    </div>
                    <p class="text-xs text-gray-500">
                        CSVs up to 10MB
                    </p>
                </div>
                <div v-else>
                    File Uploaded: {{file.target.files[0].name}}
                </div>
            </div>
            <div v-if="file" class="m-4">
                <div v-for="(value, index) in localMapping" :key="value.key">
                    <div class="flex justify-between">
                        <label class="block text-sm font-medium text-gray-700">
                            {{value.name}}
                        </label>
                        <div class="flex w-1/2">
                            <select :value="localMapping[index].value" @change="(event) => changeHeader(event.target.value, index)" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option v-if="!localMapping[index].value" disabled :value="null">Select a value</option>
                                <option v-else :value="localMapping[index].value" disabled>{{localMapping[index].value}}</option>
                                <option v-for="header in selectableHeaders" :key="'header'+header" :value="header">{{ header}}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <slot></slot> 
        </div>
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <button @click="() => saveMapping()" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Save
            </button>
        </div>
    </div>
</template>

<script>
    import {HomeIcon, PencilIcon, PhoneIcon, CloudIcon, NewspaperIcon} from "@heroicons/vue/outline";

    export default {
        name: "Finance.Dashboard",
        props: ['value', 'label'],
        data() {
            return {
                file: null,
                headers: [],
                localMapping: {},
            };
        },
        methods: {
            changeHeader(value, index) {
                this.localMapping = this.localMapping.map((item, $i) => {
                    if ($i !== index) {
                        return item;
                    }

                    return {
                        ...item,
                        value,
                    }
                });
                this.$emit('input', this.localMapping)
            },
            getCsvHeaders(fileInput)  {
                return new Promise((resolve, reject) => {
                    const file = fileInput.files[0];
                    if (!file) {
                        return reject();
                    }
                    const reader = new FileReader();
                    reader.readAsArrayBuffer(file);
                    reader.onloadend = (evt) => {
                        const data = evt.target.result;
                        const byteLength = data.byteLength;
                        const ui8a = new Uint8Array(data, 0);
                        let byteCount = 0;

                        // The elements in the array buffer correspond to the number of bytes of memory that could be consumed. IE 1 element in the array = 1 byte.
                        // To be performant (especially with larger files) we only care about the bytes for the headers
                        for (let i = 0; i < byteLength; i++) {
                            if (String.fromCharCode(ui8a[i]).match(/[^\r\n]+/g) === null) {
                                break;
                            }

                            byteCount++;
                        }

                        // If we cut the array off to limit the number of bytes we can save loads of memory and cpu cycles for performance (it also prevents the page from crashing)
                        let headerBytes = data.slice(0, byteCount);
                        // Then we have to convert the array buffer of the header's information to a UTF-8 string.
                        // And yes, it has to be UTF-8 to prevent other encoding issues with various formats.
                        let headerstring = new TextDecoder("UTF-8").decode(headerBytes);

                        resolve(
                            headerstring
                                // Split using regex to support CSVs and TSVs
                                .split(/\,|\t/g)
                                // This is regex is to remove extra " on the column names.
                                .map((i) => i.trim().replace(/((^\")|(\"$))/g, ""))
                        );
                    };
                });
            },
            changeFile(file) {
                this.file = file;
            },
            saveMapping() {
                this.$emit('save', {
                    mapping: this.localMapping.reduce((mapping, map) => ({
                ...mapping,
                [map.key]: map.value
            }), {}),
                    file: this.file,
                })
            },
        },
        watch: {
            file(file) {
                if (file && file.target) {
                    this.getCsvHeaders(file.target).then((headers) => {
                        this.headers = headers;

                        this.localMapping = this.localMapping.map((mapping) => {
                            const possibllyMatchingHeaders = this.headers.filter((header) => mapping.key.includes(header) || header.includes(mapping.key));

                            if (possibllyMatchingHeaders.length !== 0) {
                                return {
                                    ...mapping,
                                    value: possibllyMatchingHeaders[0],
                                }
                            }

                            return mapping;
                        });
                    });
                }
            },
        },
        computed: {
            routes() {
                return []
            },
            selectableHeaders() {

                // Need to filter out the headers that are already mapped
                return this.headers.filter((header) => {
                    return !this.localMapping.some((item) => item.value === header);
                });
            },
        },
        mounted() {
            this.localMapping = this.value;
        }
    }

</script>

<style scoped>

</style>
