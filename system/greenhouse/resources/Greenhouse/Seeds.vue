<template>
    <dual-menu-panel
        name="Greenhouse"
        :navigation="routes"
        redirect="/greenhouse"
        store-query-action="queryGreenhouse"
        feature="greenhouse"
    />
</template>

<script>
import { HomeIcon, SparklesIcon } from '@heroicons/vue/outline';
import Plant from '@icons/Plant';

export default {
    components: {
      
    },
    computed: {
        routes() {
            return [
                {
                    name: 'Seeds',
                    href: '/greenhouse/seeds',
                    icon: SparklesIcon,
                    current: false,
                },
                {
                    name: 'Plants',
                    href: '/greenhouse/plants',
                    icon: Plant,
                    current: false,
                },
            ]
        }
    },
}
</script>