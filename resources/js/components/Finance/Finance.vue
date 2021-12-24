<template>
    <dual-menu-panel
        name="Finance"
        :navigation="routes"
        redirect="/finance"
        store-query-action="queryFinance"
    />
</template>

<script>
    import DualMenuPanel from "../DualMenuPanel";
    import {HomeIcon, PencilIcon, PhoneIcon, CogIcon } from "@heroicons/vue/outline";

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
                        href: 'dashboard',
                        icon: HomeIcon,
                        current: false,
                    },
                    {
                        name: 'Settings',
                        href: 'settings',
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
