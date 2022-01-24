<template>
    <div>
        <div class="text-left">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-50" id="modal-title">
                Research Name
            </h3>
            <div class="mt-2">
                <spork-input v-model="modelValue.name" placeholder="Topic of study..." type="text"/>
            </div>
        </div>    
    </div>
</template>

<script>
export default {
    props: ['modelValue']
}
</script>