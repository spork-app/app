<template>
    <dual-menu-panel
        name="Seeds"
        :navigation="routes"
        redirect="/seeds"
        store-query-action="querySeeds"
        feature="seeds"
    />
</template>

<script>
import { HomeIcon, SparklesIcon } from '@heroicons/vue/outline';

export default {

    computed: {
        routes() {
            return [
                {
                    name: 'Home',
                    href: '/seeds',
                    icon: HomeIcon,
                    current: false,
                },
                {
                    name: 'Seeds',
                    href: '/seeds/crud',
                    icon: SparklesIcon,
                    current: false,
                },
                {
                    name: 'Plants',
                    href: '/plants/crud',
                    icon: SparklesIcon,
                    current: false,
                },
            ]
        }
    },
}
</script>