<template>
    <div class="flex flex-col gap-2 px-4 bg-gray-700 p-2 -mt-2">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: "SubNavItem",
    props: ['parentTo'],

    computed: {
        parentActive() {
            return this.$route.fullPath.includes(this.parentTo) && this.$route.fullPath !== this.parentTo;
        },

    }
}
</script>

<style scoped>

</style>
