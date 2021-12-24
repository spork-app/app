<template>
    <dual-menu-panel
        name="Food"
        :navigation="routes"
        redirect="/food"
        store-query-action="queryFood"
    />
</template>

<script>
    import DualMenuPanel from "../DualMenuPanel";
    import {HomeIcon, PencilIcon, PhoneIcon, CloudIcon, NewspaperIcon} from "@heroicons/vue/outline";

    export default {
        name: "Miscellaneous",
        components: {
            DualMenuPanel,
        },

        computed: {
            routes() {
                return [
                    {
                        name: 'Dashboard',
                        href: '/food/dashboard',
                        icon: HomeIcon,
                        current: false,
                    },
                    {
                        name: 'Nugs',
                        href: '/food',
                        icon: PhoneIcon,
                        current: false,
                    },
                ]
            }
        },
        mounted() {
        }
    }

</script>

<style scoped>

</style>
