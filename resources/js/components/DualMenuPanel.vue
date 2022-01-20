<template>
    <div class="flex w-full min-h-full text-gray-600 dark:text-gray-200">
        <div style="max-width: 300px;" class="w-14 overflow-hidden xl:w-64 px-2 bg-white dark:bg-gray-700 min-h-full flex flex-col border-r border-gray-200 dark:border-gray-400">
            <router-link :to="redirect" class="text-lg py-6 ">{{ name }}</router-link>
            <div class="relative mt-3">
                <input :value="$store.getters.searchQuery" @keyup="(value) => debounce(() => $store.commit('query', value.target.value))" type="text" class="w-full bg-gray-100 border dark:bg-gray-800 border-gray-200 dark:border-gray-600 dark:text-gray-100 rounded-lg py-2 pr-4 pl-5 xl:pl-10" placeholder="Bananas">
                <svg class="absolute top-0 mt-2 ml-2 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            <div class="space-y-1 mt-2">
                <template v-for="item in routes" :key="item.name">
                    <div v-if="!item.children">
                        <router-link :to="item.href" :class="[item.current ? 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200' : 'hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-800 dark:hover:text-gray-200', 'group w-full flex items-center pl-2 py-2 text-sm font-medium rounded-md text-left']">
                            <component :is="item.icon" :class="['mr-4 flex-shrink-0 h-6 w-6']" aria-hidden="true" />
                            {{ item.name }}
                        </router-link>
                    </div>
                    <Disclosure as="div" v-else class="space-y-1" v-slot="{ open }">
                        <DisclosureButton :class="[item.current ? 'bg-gray-100 text-gray-700 dark:text-gray-200' : 'hover:bg-gray-200 dark:hover:bg-gray-800 dark:hover:text-gray-100 hover:text-gray-700',
                     'group w-full flex items-center pl-2 pr-1 py-2 text-left text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500']">
                            <component :is="item.icon" class="mr-4 flex-shrink-0 h-6 w-6 text-gray-700 dark:text-gray-400 group-hover:text-gray-800 dark:group-hover:text-gray-200  dark:hover:text-gray-200" aria-hidden="true" />
                            <span class="flex-1 xl:block hidden text-left">
                                {{ item.name }}
                            </span>
                            <svg :class="[open ? 'rotate-90' : '', 'text-gray-700 dark:text-gray-400 ml-4 flex-shrink-0 h-5 w-5 transform group-hover:text-gray-800 dark:group-hover:text-gray-200  transition-colors ease-in-out duration-150']" viewBox="0 0 20 20" aria-hidden="true">
                                <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
                            </svg>
                        </DisclosureButton>
                        <DisclosurePanel class="space-y-1 text-left">
                            <router-link v-for="subItem in item.children" :key="subItem.name" :to="subItem.href" :class="[subItem.current ? 'text-gray-600 dark:text-gray-200' : 'text-gray-500 dark:text-gray-100']" class="group text-left w-full flex justify-start items-center py-2 text-sm font-medium rounded-md text-gray-700 hover:text-gray-800 hover:bg-gray-200 dark:hover:bg-gray-600 dark:hover:text-gray-200">
                                <component v-if="subItem.icon" :is="subItem.icon" class="mr-4 flex-shrink-0 h-6 w-6 text-gray-800 dark:text-gray-300 group-hover:text-gray-800 dark:group-hover:text-gray-200 ml-2" aria-hidden="true" />
                                <span :class="['xl:block hidden', subItem.icon ? '' : 'ml-12']">{{ subItem.name }}</span>
                                <span class="block xl:hidden pl-3">{{ subItem.name[0] }}</span>
                            </router-link>
                        </DisclosurePanel>
                    </Disclosure>
                </template>
            </div>

            <div class="border border-gray-100 w-full mt-4"></div>
<!--            <div class="flex flex-col xl:block hidden gap-4 mt-4 text-base text-gray-800 pl-10">-->
<!--                <div class="uppercase text-xs font-bold text-gray-600">Stores</div>-->
<!--                <div>-->
<!--                    Meijer-->
<!--                </div>-->
<!--            </div>-->
        </div>

        <div class="flex flex-col w-full">
            <router-view></router-view>
        </div>
    </div>

</template>

<script>
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
const mapCurrentRoute = (fullPath, item) => {
    if (typeof item.children === 'object') {
        item.children = item.children.map(i => mapCurrentRoute(fullPath, i))

        return item;
    }

    item.current = fullPath === item.href
    return item;
};

export default {
    components: { Disclosure, DisclosureButton, DisclosurePanel },
    name: "DualMenuPanel",
    props: ['name', 'redirect', 'navigation', 'storeQueryAction'],
    watch: {
        '$store.getters.searchQuery'(newVal, oldVal) {
            this.$store.dispatch(this.storeQueryAction)
            if (window.location.pathname !== this.redirect) {
                this.$router.push({ path: this.redirect})
            }
        },
    },
    computed: {
        routes() {
            return this.navigation.map((item) => mapCurrentRoute(this.$route.fullPath, item))
        }
    },

    setup() {
        function createDebounce() {
            let timeout = null;
            return function (fnc, delayMs) {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    fnc();
                }, delayMs || 500);
            };
        }

        return {
            debounce: createDebounce(),
        }
    }
}
</script>

<style scoped>

</style>
