<template>
    <dual-menu-panel
        name="Store"
        :navigation="routes"
        redirect="/shopping"
        store-query-action="queryStore"
    />

</template>

<script>
import {CreditCardIcon, HomeIcon, ShoppingCartIcon} from "@heroicons/vue/outline";
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import DualMenuPanel from "../DualMenuPanel";

export default {
    components: { DualMenuPanel },
    name: "Shopping",
    computed: {
        routes() {
            return [
                {
                    name: 'Shoppe',
                    href: '/shopping',
                    icon: HomeIcon,
                    current: false,
                },
                {
                    name: 'Past Orders',
                    href: '/shopping/orders',
                    icon: CreditCardIcon,
                    current: false,
                },
                {
                    name: 'Cart',
                    href: '/shopping/cart',
                    icon: ShoppingCartIcon,
                    current: false,
                },
                {
                    name: 'ShoppingLists',
                    href: '#',
                    icon: HomeIcon,
                    current: false,
                    children: this.$store.getters.lists.map(list => ({
                        name: list.name,
                        href: '/shopping/lists/' + list.id,
                        current: false,
                    }))
                },
            ]
        }
    },
    mounted() {
        this.$store.dispatch('getShoppingLists');
    }
}
</script>

<style scoped>

</style>
