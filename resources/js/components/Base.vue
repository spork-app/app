<template>
    <div class="w-full min-h-full flex bg-gray-800">
        <div class="w-14 2xl:w-64 overflow-hidden flex flex-col justify-between">
            <div class="flex flex-col gap-2 text-gray-200">
                <div class="p-4 flex items-center gap-4 text-2xl">
                    <svg class="h-10" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 583 583"><circle cx="291.5" cy="291.5" r="291.5" fill="#15bc9c"/><circle cx="340.75" cy="284.46" r="30.16" fill="#8adece"/><path fill="#fff" d="M230.18 427.2V115.59h-48.24V471.43h221.13V427.2H230.18z"/></svg>
                    <span class="2xl:block hidden">Special Fiesta</span>
                </div>

                <nav class="flex-1 px-2 space-y-1" aria-label="Sidebar">
                    <template v-for="item in navigation" :key="item.name">
                        <div v-if="!item.children">
                            <router-link :to="item.href" :class="[item.current ? 'bg-gray-900 text-gray-100' : 'bg-gray-800 text-gray-200 hover:bg-gray-700 hover:text-gray-200', 'group w-full flex items-center pl-2 py-2 text-sm font-medium rounded-md']">
                                <component :is="item.icon" :class="[item.current ? 'text-gray-100' : 'text-gray-200 group-hover:text-gray-300', 'mr-4 flex-shrink-0 h-6 w-6 stroke-current']" aria-hidden="true" />
                                {{ item.name }}
                            </router-link>
                        </div>
                        <Disclosure as="div" v-else class="space-y-1" v-slot="{ open }">
                            <DisclosureButton :class="[item.current ? 'bg-gray-900 text-gray-100' : 'bg-gray-800 text-gray-200 hover:bg-gray-700 hover:text-gray-50', 'group w-full flex items-center pl-2 pr-1 py-2 text-left text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500']">
                                <component :is="item.icon" class="mr-4 flex-shrink-0 h-6 w-6 text-gray-200 group-hover:text-gray-50 stroke-current" aria-hidden="true" />
                                <span class="flex-1 2xl:block hidden">
                                    {{ item.name }}
                                </span>
                                <svg :class="[open ? 'rotate-90' : '', 'text-gray-100 ml-4 flex-shrink-0 h-5 w-5 transform group-hover:text-gray-50 transition-colors ease-in-out duration-150']" viewBox="0 0 20 20" aria-hidden="true">
                                    <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
                                </svg>
                            </DisclosureButton>
                            <DisclosurePanel class="space-y-1">
                                <router-link v-for="subItem in item.children" :key="subItem.name" :to="subItem.href" class="group w-full flex items-center pr-2 py-2 text-sm font-medium rounded-md hover:text-gray-100 hover:bg-gray-700">
                                    <component :is="subItem.icon" class="mr-4 flex-shrink-0 h-6 w-6 text-gray-200 group-hover:text-gray-50 ml-2 xl:hidden block stroke-current" aria-hidden="true" />
                                    <span class="xl:block hidden xl:pl-12">{{ subItem.name }}</span>
                                </router-link>
                            </DisclosurePanel>
                        </Disclosure>
                    </template>
                </nav>

            </div>

            <div class="flex w-full justify-between items-center p-4 text-gray-100">
                <div class="flex flex-wrap gap-2 items-center">
                    <div class="w-8 h-8 rounded-full overflow-hidden">
                        <img src="https://pbs.twimg.com/profile_images/1244859550169206784/AWGcu5Hc_400x400.jpg" alt="User photo"/>
                    </div>
                    <div class="flex flex-col">
                        <div class="text-sm font-medium">Austin Kregel</div>
                        <div class="text-xs">1411 Highland Drive</div>
                    </div>
                </div>
                <router-link to="/settings">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </router-link>
            </div>
        </div>
        <div class="flex-1 bg-gray-50">
            <router-view v-if="!$store.state.FeatureStore.loading"></router-view>
            <div v-else class="flex items-center justify-center text-xl w-full h-full">
                <refresh-icon class="animate-rotate w-8 h-8" />
                <div class="ml-4">Loading features!</div>
            </div>
        </div>
    </div>
</template>
<script>
import { ref } from 'vue'
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import {
    CalendarIcon,
    ViewBoardsIcon,
    CogIcon,
    HomeIcon,
    TruckIcon,
    ShoppingCartIcon,
    BeakerIcon,
    LibraryIcon,
    PhoneIcon,
    RefreshIcon,
} from '@heroicons/vue/outline'

import Chunk from './Chunk';

const mapCurrentRoute = (fullPath, item) => {
    if (typeof item.children === 'object') {
        item.children = item.children.map(i => mapCurrentRoute(fullPath, i))

        return item;
    }

    item.current = fullPath === item.href || fullPath.startsWith(item.href);
    return item;
};

export default {
    components: {
        Disclosure,
        DisclosureButton,
        DisclosurePanel,
        Chunk,
        RefreshIcon,
    },
    setup() {
        return {
            open: ref(false)
        }
    },
    computed: {
        navigation: function () {
            return [
                {name: 'Home', icon: HomeIcon, href: '/home', },
                {
                    name: 'Finance', icon: LibraryIcon, children: [
                        {name: 'Dashboard', icon: LibraryIcon, href: '/finance/dashboard'},

                    ]
                },
                {name: 'Planning', icon: ViewBoardsIcon, href: '/planning'},
                {name: 'Calendar', icon: CalendarIcon, href: '/calendar'},
                {
                    name: 'Maintenance', href: '/maintenance', icon: CogIcon, children: [
                        {name: 'Properties', icon: HomeIcon, href: '/maintenance/properties'},
                        {name: 'Garage', icon: TruckIcon, href: '/maintenance/garage'}
                    ]
                },
                {name: 'Shopping', icon: ShoppingCartIcon, href: '/shopping'},
                {name: 'Research', icon: BeakerIcon, href: '/research'},
                {
                    name: 'Miscellaneous', href: '/miscellaneous', icon: CogIcon
                },
                {name: 'Food', icon: Chunk, href: '/food'},

            ].map((item) => mapCurrentRoute(this.$route.fullPath, item))
        }
    }
}
</script>
