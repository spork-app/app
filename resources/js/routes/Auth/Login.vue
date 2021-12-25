<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <a href="/">
                Logo
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <ValidationErrors :errors="$store.state.Authentication.errors"></ValidationErrors>
            <div class="mt-4"></div>
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" placeholder="Email" v-model="form.email">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Password
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" placeholder="******************" v-model="form.password">
                </div>
                <div class="flex items-center justify-between">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" :class="[$store.state.Authentication.loading ? 'opacity-75 cursor-not-allowed': '']" type="submit" :disabled="$store.state.Authentication.loading">
                        <span>Sign<span v-if="$store.state.Authentication.loading">ing</span> In</span>

                    </button>
                    <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="#">
                        Forgot Password?
                    </a>
                </div>
            </form>
        </div>
    </div>
</template> 

<script>
import ValidationErrors from '@components/ValidationErrors.vue';

export default {
    components: {
        ValidationErrors,
    },
    data() {
        return {
            form: {
                email: '',
                password: ''
            },
        }
    },
    methods: {
        submit() {
            this.$store.dispatch('login', this.form)
        }
    },
    watch: {
        '$store.getters.isAuthenticated': function(old, newVal) {
            if (newVal) {
                this.$router.push('/planning');
            }
        }
    }
}
</script>