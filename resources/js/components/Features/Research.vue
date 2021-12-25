<template>
    <div>
        <div class="text-left">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                Research Name
            </h3>
            <div class="mt-2">
                <input v-model="modelValue.name" placeholder="Topic of study..." type="text" class="block max-w-lg w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md"/>
            </div>
        </div>    
    </div>
</template>

<script>
export default {
    props: ['modelValue']
}
</script>