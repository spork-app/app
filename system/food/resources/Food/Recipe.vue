<style scoped>

</style>

<template>
    <div class="w-full lg:w-1/2">
        <div class="mx-2 my-4 rounded overflow-hidden shadow-lg bg-white">
            <img class="w-full" :src="recipe.imageLink.endsWith('.jpg') ? recipe.imageLink : recipe.imageLink + 'jpg'" alt="Sunset in the mountains">
            <div class="px-6 py-4">
                <div class="font-bold text-xl mb-2">
                    <a :href="recipe.slug">{{ recipe.name }}</a>
                    <div class="text-sm">{{ recipe.headline }}</div>
                </div>
                <p class="text-grey-darker text-base">
                    {{ recipe.description }}
                </p>
            </div>
            <div class="px-6 pb-3 flex flex-wrap">
                <span v-if="recipe.prepTime" class="bg-grey-lighter rounded-full px-3 py-1 text-sm font-semibold text-grey-darker mr-2 mb-2">#prep-time-{{ recipe.prepTime.replace('PT', '').toLowerCase()}}</span>
                <span v-if="recipe.difficulty === 1" class="bg-grey-lighter rounded-full px-3 py-1 text-sm font-semibold text-grey-darker mr-2 mb-2">#difficulty-easy</span>
                <span v-else-if="recipe.difficulty === 2" class="bg-grey-lighter rounded-full px-3 py-1 text-sm font-semibold text-grey-darker mr-2 mb-2">#difficulty-medium</span>
                <span v-else-if="recipe.difficulty === 3" class="bg-grey-lighter rounded-full px-3 py-1 text-sm font-semibold text-grey-darker mr-2 mb-2">#difficulty-moderate</span>
                <span v-else-if="recipe.difficulty === 4" class="bg-grey-lighter rounded-full px-3 py-1 text-sm font-semibold text-grey-darker mr-2 mb-2">#difficulty-hard</span>
                <span v-for="allergen in recipe.allergens" class="bg-grey-lighter rounded-full px-3 py-1 text-sm font-semibold text-grey-darker mr-2 mb-2">#{{ allergen.type }}</span>
                <span v-if="recipe.wines.length > 0" class="bg-grey-lighter rounded-full px-3 py-1 text-sm font-semibold text-grey-darker mb-2">#pairs-with-wine</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['recipe'],
        data() {
            return {}
        },
        mounted() {

        }
    }
</script>
