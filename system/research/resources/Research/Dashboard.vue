<template>
    <div class="flex flex-wrap gap-4 m-4">
        <feature-required feature="research" allow-more-than-one></feature-required>
        <div class="w-full font-medium text-gray-600 dark:text-gray-300 uppercase ml-3">Recent</div>
        <router-link :to="'/research/'+ topic.id" v-for="(topic, i) in $store.getters.topics" class="p-3 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-600" :key="i">
            <div  class="font-medium">{{ topic.name }}</div>
            <pre class="w-64 h-48 shadow-inset overflow-hidden text-xs border-t py-2 my-2">{{ topic.settings.body }}</pre>
            <div class="text-gray-500 dark:text-gray-200 border-t mt-4 pt-2">{{ date(topic.updated_at) }}</div>
        </router-link>
    </div>
</template>

<script>
import FeatureRequired from '@components/FeatureRequired.vue';

export default {
  components: { FeatureRequired },
    name: "Dashboard.vue",
    data() {
        return {
        }
    },
    methods: {
        date(date) {
            return dayjs(date).format('lll')
        }
    }
}
</script>
