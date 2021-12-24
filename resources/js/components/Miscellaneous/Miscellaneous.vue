<template>
    <dual-menu-panel
        name="Miscellaneous"
        :navigation="routes"
        redirect="/miscellaneous"
        store-query-action="queryMisc"
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
                        name: 'Home',
                        href: '/miscellaneous',
                        icon: HomeIcon,
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
