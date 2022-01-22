<template>
<div>
    <input type="file" @drop="uploadFile" @input="uploadFile" ref="fileUpload" class="fixed left-0" style="top: -99999px;" accept=".csv"/>
    <button @click="openDialog" class="flex items-center border-gray-400 border rounded py-2 px-4 focus:shadow-lg focus:bg-gray-1000">
        <span>Secure Upload</span>
        <span class="ml-2">
            <loading-animation v-if="loading" class="w-4 h-4"/>
            <svg v-else-if="success" viewBox="0 0 20 20" fill="currentColor" class="text-green-500 w-4 h-4"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        </span>
    </button>
</div>
</template>

<script>
export default {
    props: ['type'],
    data: () => ({
        loading: false,
        success: false,
    }),
    methods: {
        async uploadFile(fileEvent) {
            if (fileEvent.target.files.length === 0) {
                return;
            }
            this.loading = true;
            const mypostparameters= new FormData()
            mypostparameters.append('file', fileEvent.target.files[0]);
            await axios.post('/csv/' + this.type, mypostparameters);
            this.loading = false;
            this.success = true;
            setTimeout(() => {
                this.success = false
            }, 2000)
        },
        openDialog() {
            this.$refs.fileUpload.click();
        }
    },
}
</script>
