<style scoped>

</style>

<template>
    <div>
        <div class="flex flex-wrap items-center justify-between mb-4">
            <h4 class="text-xl">Accounts</h4>
            <div class="flex flex-wrap justify-between">
                <button @click.prevent="open = true">
                    Create a manual account
                </button>
                <button @click="() => linkAccount()" class="text-white p-2 rounded shadow ml-4" :class="{ 'bg-blue-500': !$store.getters.darkMode, 'bg-blue-700': $store.getters.darkMode }">
                    Link through Plaid
                </button>
            </div>
        </div>

        <div class="shadow p-4 rounded" :class="{'bg-gray-700': $store.getters.darkMode, 'bg-white': !$store.getters.darkMode}">
            <div v-if="$store.getters.features?.finance" class="-mt-4">
                <div v-for="token in $store.getters.features?.finance" :key="'token'+token.id" class="flex flex-wrap mt-4">
                    <div class="text-gray-800 text-lg font-medium">
                        {{ token.name }}
                    </div>
                    <div class="flex w-full text-gray-500" v-if="token?.accounts?.length > 0">
                        <div class=" text-sm">{{ token.accounts.map(a => a.name).join(', ')}}</div>
                    </div>
                    <div class="flex w-full text-gray-500 text-sm italic" v-else>No accounts linked</div>
                </div>
            </div>
            <div v-else class="text-center italic">
                No accounts connected...
            </div>
        </div>
        <!-- This example requires Tailwind CSS v2.0+ -->
        <div v-if="open" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div>
                        <div class="text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Name
                            </h3>
                            <div class="mt-2">
                                <input v-model="form.name" placeholder="Subscriptions" type="text" class="block max-w-lg w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md"/>
                            </div>
                        </div>
                    </div>
                    <div class="my-4">
                        <div class="text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Type
                            </h3>
                            <div class="mt-2">
                                <select v-model="form.type" class="block max-w-lg w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md">
                                    <option value="checking">Checking</option>
                                    <option value="savings">Savings</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Balance
                            </h3>
                            <div class="mt-2">
                                <input v-model="form.balance" placeholder="Subscriptions" type="text" class="block max-w-lg w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md"/>
                            </div>
                        </div>
                    </div>
                    <div class="my-4">
                        <div class="text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Finance Account (Link)
                            </h3>
                            <div class="mt-2">
                                <select v-model="form.type" class="block max-w-lg w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md">
                                    <option v-for="account in $store.getters.features.finance" :key="account.account_id" :value="account.id">{{ account.name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                        <button
                            @click="createAccount"
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm"
                        >
                            Create New Account
                        </button>
                        <button @click="open = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                handler: null,
                open: false,
                form: {
                    name: '',
                    type: 'checking',
                    balance: 0.0,
                    feature_list_id: null
                }
            }
        },
        methods: {
            date(date) {
                return dayjs(date);
            },
            async createAccount() {
                this.$store.dispatch('createAccount', this.form);
            },
            async setupPlaid(accessToken = null) {
                if (this.handler) {
                    return;
                }
                const fetchLinkToken = async () => {
                    const { data } = await axios.post(!accessToken ? '/api/plaid/create-link-token' : '/api/plaid/update-access-token',  accessToken ? {
                        access_token: accessToken?.id
                    } : {});
                    return data.link_token;
                };

                const configs = {
                    token: await fetchLinkToken(),
                    onSuccess: async (public_token, {institution: {institution_id}}) => {
                        if (accessToken) {
                            return;
                        }

                        console.log({ public_token })

                        const { data: token } = await axios.post('/api/plaid/exchange-token', {
                            public_token: public_token,
                            institution: institution_id
                        });

                        await this.$store.dispatch('getFeatureLists', {  })
                    },
                    onExit: async function (err, metadata) {
                        if (err != null && err.error_code === 'INVALID_LINK_TOKEN') {
                            this.handler.destroy();
                            this.handler = Plaid.create({
                                ...configs,
                                token: await fetchLinkToken(),
                            });

                        }
                        if (err != null) {
                            console.log(err.message || err.error_code, this.$toasted);
                            return;
                        }
                    },
                };
                this.handler = Plaid.create(configs)
            },
            async linkAccount() {
                await this.setupPlaid();
                this.handler.open();
            }
        },
        computed: {
            accessTokens() {
                return this.$store.getters.accessTokens
            }
        },
        mounted() {
        }
    }
</script>
