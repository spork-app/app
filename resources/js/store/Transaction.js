export default {
    state: {
        transactions: [],
        pagination: {},
        loading: false,
        errors: null,
    },
    getters: {
        transactions: state => state.transactions
    },
    actions: {
        async getTransactions({ state }, account) {
            state.loading = true;
            try {
                const { data } = await axios.get(buildUrl('/api/transaction', {
                    filter: {
                        for_accounts: true
                    },
                    sort: '-date',
                    action: 'paginate:100'
                }));
                const { data: things, ...pagination} = data;

                state.pagination = pagination;
                state.transactions = things;

            } catch (e) {
                if (e.response.status === 422) {
                    state.errors = e.response.data
                }
            } finally {
                setTimeout(()=> state.loading = false, 500);
            }
        }
    },
};

