<template>
    <div v-if="partiallyActive && !active"
         :class="{
            'hover:bg-gray-50 bg-gray-200 text-white': active && !submenu,
            'hover:bg-gray-50': !active && !partiallyActive,
            'bg-gray-300 text-white': partiallyActive || active,
            'p-2 mx-2': !submenu,
            'py-2 px-4': submenu,
        }"
    >


        <router-link
            :to="to"
            class="flex gap-2 rounded items-center">
            <slot></slot>
            <span class="font-medium">{{ name }}</span>
        </router-link>
    </div>

    <router-link
        v-else
        :to="to"
                 :class="{
        'hover:bg-gray-700 bg-gray-900 text-white': active,
        'hover:bg-gray-700': !active && !partiallyActive,
        'bg-gray-200': partiallyActive,

        'mx-2': true,
    }"
                 class="flex gap-2 p-2 rounded items-center">
        <slot></slot>
        <span class="font-medium">{{ name }}</span>
    </router-link>
</template>

<script>
export default {
    props: [
        'to',
        'name',
        'submenu'
    ],

    computed: {
        active() {
            return this.$route.fullPath === this.to
        },
        partiallyActive() {
            return this.$route.fullPath.includes(this.to) && !this.active;
        }
    }
}
</script>

<style scoped>

</style>
