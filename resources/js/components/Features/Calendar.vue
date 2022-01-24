<template>
    <div>
        <div class="">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                Name
            </h3>
            <div class="mt-2">
                <input v-model="modelValue.name" placeholder="Subscriptions" type="text" class="block max-w-lg w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md"/>
            </div>
        </div>
        <div class="mt-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                Amount
            </h3>
            <div class="mt-2">
                <input v-model="modelValue.amount" placeholder="100.50" type="text" class="block max-w-lg w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md"/>
            </div>
        </div>
        <div class="mt-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                Groups
            </h3>
            <div class="mt-2 p-2 text-sm font-normal flex flex-col gap-2 border border-gray-200 rounded-lg max-h-32 overflow-y-scroll">
                <label class="cursor-pointer">
                    <input v-model="modelValue.groups" type="checkbox" value="1" class="mr-2 rounded" />
                    Subscription
                </label>    
                
                <label class="cursor-pointer">
                    <input v-model="modelValue.groups" type="checkbox" value="1" class="mr-2 rounded" />
                    Utility
                </label>
                
                <label class="cursor-pointer">
                    <input v-model="modelValue.groups" type="checkbox" value="1" class="mr-2 rounded" />
                    Loans
                </label>
                <label class="cursor-pointer">
                    <input v-model="modelValue.groups" type="checkbox" value="1" class="mr-2 rounded" />
                    Holiday Spending
                </label>
                <label>
                    <input v-model="modelValue.groups" type="checkbox" value="1" class="mr-2 rounded" />
                    Fast Food
                </label>
                <label>
                    <input v-model="modelValue.groups" type="checkbox" value="1" class="mr-2 rounded" />
                    Hobbies
                </label>
                <label>
                    <input v-model="modelValue.groups" type="checkbox" value="1" class="mr-2 rounded" />
                    Servers
                </label>
                <label>
                    <input v-model="modelValue.groups" type="checkbox" value="1" class="mr-2 rounded" />
                    Other Tech
                </label>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['modelValue']
}
</script>