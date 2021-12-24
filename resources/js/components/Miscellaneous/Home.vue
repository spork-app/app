<template>
    <div class="w-full flex flex-wrap">
        <div class="w-1/2">
            <news></news>
        </div>

        <div class="w-1/2">
            <weather></weather>
        </div>
    </div>
</template>

<script>
import News from "./News.vue"
import Weather from './Weather.vue'

export default {
    name: 'Home',
    components: { News, Weather },
    data() {
        return {
            //
        }
    },
    methods: {
        //
    }
}
</script>