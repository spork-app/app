<template>
    <div v-if="hasErrors">
        <div class="font-medium text-red-600">Whoops! Something went wrong.</div>

        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
            <li v-for="(moreErrors, key) in errors" :key="key"><div class="inline" v-for="(error, i) in moreErrors" :key="'err'+i">{{ error }}</div></li>
        </ul>
    </div>
</template>

<script>
export default {
    props: ['errors'],
    computed: {
        hasErrors() {
            if (!this.errors) {
                return false;
            }

            return Object.keys(this.errors).length > 0
        },
    }
}
</script>
