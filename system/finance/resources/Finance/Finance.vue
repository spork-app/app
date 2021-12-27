<template>
    <dual-menu-panel
        name="Finance"
        :navigation="routes"
        redirect="/finance"
        store-query-action="queryFinance"
    />
</template>

<script>
    import DualMenuPanel from "@components/DualMenuPanel";
    import {HomeIcon, PencilIcon, PhoneIcon, CogIcon } from "@heroicons/vue/outline";

    export default {
        name: "Finance",
        components: {
            DualMenuPanel,
        },

        computed: {
            routes() {
                return [
                    {
                        name: 'Dashboard',
                        href: '/finance/dashboard',
                        icon: HomeIcon,
                        current: false,
                    },
                    {
                        name: 'Settings',
                        href: '/finance/settings',
                        icon: CogIcon,
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
