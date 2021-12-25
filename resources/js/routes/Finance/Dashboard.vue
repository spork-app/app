<template>
    <div class="flex flex-wrap mt-4">
        <div class="w-full py-2 px-4 text-2xl font-bold text-gray-800">Banking Dashboard</div>
        <div class="w-full pb-8 px-4 text-base font-base text-gray-500">Welcome Back, {{$store.getters.user.name}}!</div>

        <div class="grid grid-cols-4 gap-4 w-full px-4">
            <div class="min-w-1/4 flex-1" v-for="(account, i) in $store.getters.allAccountsFromFeatures.slice(0, 8)" :key="'dashboard'+i">
                <div class="bg-white shadow rounded-xl flex flex-col w-full p-4">
                    <div class="text-gray-600">{{account.name}}</div>

                    <div class="flex flex-col my-8 ">
                        <div class="text-3xl font-medium text-gray-900">
                            ${{account?.available?.toFixed(2)?.toLocaleString() ?? 0}}
                            <span class="text-base font-base">/ {{account?.balance?.toFixed(2)?.toLocaleString()}}</span>
                        </div>
                        <div class="text-sm font-thin">available / balance</div>
                    </div>

                    <div class="w-full flex flex-wrap">
                        <time :datetime="account.updated_at" class="text-gray-500 text-sm">{{ dayjs(account.updated_at).format('lll') }}</time>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="$store.getters.allAccountsFromFeatures.length > 8" class="text-right w-full pt-4 px-4">
            <a href="/finance/accounts">
                View all accounts
            </a>
        </div>



        <div class="w-1/2">
            <div class="m-4 text-xl font-medium">My Transactions</div>
            <div class="bg-white shadow overflow-hidden sm:rounded-md m-4">
                <ul role="list" class="divide-y divide-gray-200 h-96 overflow-y-scroll">
                    <li v-for="transaction in $store.getters.transactions" :key="transaction.transaction_id" class="flex justify-between px-4 py-2">
                        <div class="flex flex-col">
                            <div class="font-medium text-lg">
                                {{ transaction.name }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ transaction.date }} - {{ transaction.account.name }}
                            </div>
                        </div>
                        <div>
                            <div class="text-lg font-bold">
                                ${{ transaction.amount.toFixed(2) }}
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="w-1/2">
            <div class="m-4 text-xl font-medium">...?</div>
            <div class="bg-white shadow overflow-hidden sm:rounded-md m-4 p-4">
                ....?
            </div>
        </div>

    </div>
</template>

<script>
import { CheckCircleIcon, ChevronRightIcon, MailIcon } from '@heroicons/vue/solid'
import CategoryIcon from "@components/CategoryIcon";

export default {
    components: {
        CheckCircleIcon,
        ChevronRightIcon,
        MailIcon,
        CategoryIcon,
    },
    setup() {
        return {
            dayjs
        }
    },
    mounted() {
        this.$store.dispatch('getTransactions')
    }
}
</script>
