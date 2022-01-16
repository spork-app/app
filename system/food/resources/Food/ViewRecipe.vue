<style scoped>

</style>

<template>
    <div class="w-full md:w-1/2 mx-auto">
        <div class="flex flex-wrap">
            <div class="w-full md:w-1/2">
                <div class="mx-3 mb-4">
                    <div class="text-4xl">{{ recipe.name}}</div>
                    <div class="text-lg font-bold text-grey-darker">{{ recipe.headline}}</div>
                </div>
                <div class="mx-3 mb-2" v-html="description"></div>
            </div>
            <div class="w-full md:w-1/2 pr-4">
                <img :src="recipe.imageLink" class="rounded">
            </div>
        </div>

        <div class="flex flex-wrap pt-2">
            <div class="mx-3 my-3 font-bold flex-grow">
                Preparation Time: {{ preperationTime }}
            </div>
            <a :href="calendarLink"
                class="bg-blue py-3 px-4 mr-4 shadow rounded-lg text-blue-lightest hover:text-blue-lightest hover:bg-blue-dark hover:shadow-lg"
            >Add to your calendar</a>
        </div>

        <div v-if="recipe.allergens && recipe.allergens.length > 0" class="my-4">
            <generic :items="recipe.allergens" :width="class_width(recipe.allergens)" class="shadow p-4 mx-3 rounded bg-yellow-lightest">
                <div class="text-2xl mb-4 font-bold items-center flex text-yellow-darkest">
                    <svg class="fill-current text-yellow-dark w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg>
                    Allergens
                </div>
            </generic>
        </div>

        <div v-if="recipe.utensils && recipe.utensils.length > 0" class="my-4">
            <generic :items="recipe.utensils"  :width="class_width(recipe.utensils)" class="shadow p-4 mx-3 rounded bg-white">
                <div class="text-2xl mb-4 font-bold">
                    Utensils
                </div>
            </generic>
        </div>

        <ingredients :ingredients="recipe.ingredients"></ingredients>

        <div class="flex flex-wrap mt-2" v-for="row in steps">
            <div v-for="(step, $i) in row" :class="class_width(row)">
                <div class="mx-3 my-3 rounded overflow-hidden shadow-lg bg-white">
                    <img class="w-full" v-if="step.images" :src="step.images" :alt="step.instructions">
                    <div class="px-6 py-4">
                        <div v-if="step.ingredients && step.ingredients.length > 0">
                            <div class="my-2 text-xl">Ingredients</div>
                            <ul class="my-2">
                                <li v-for="ingredient in step.ingredients">{{ ingredient.name }}</li>
                            </ul>
                            <hr>
                        </div>
                        <p class="text-grey-darker text-base" v-html="md(step.instructionsMarkdown)"></p>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="recipe.wines && recipe.wines.length > 0" class="mt-4">
            <generic :items="recipe.wines" width="w-full" class="shadow p-4 mx-3 rounded bg-white">
                <div class="text-2xl mb-4 font-bold">
                    Suggested Wines
                </div>
            </generic>
        </div>
    </div>
</template>

<script>
    import CalLink from 'calendar-link';
    import dayjs from 'dayjs';
    const array_chunks = (array, chunk_size) => {
        let arr = [];
        let chunk = [];
        for (let i = 0; i < array.length; i++) {
            let tmp = array[i];

            if (tmp.images === null) {
                if (chunk.length !== 0) {
                    arr.push(chunk);
                    chunk = [];
                }
                chunk.push(tmp);
                arr.push(chunk);
                chunk = [];
                continue;
            }

            if (chunk.length === chunk_size) {
                arr.push(chunk);
                chunk = [];
            }

            chunk.push(tmp);
        }

        return arr;
    }
    const markdown = require('markdown-it')()
    const calendarLink = new CalLink()
    export default {
        props: ['recipe'],
        data() {
            return {
                steps: []
            }
        },
        computed: {
            description() {
                return markdown.render(this.recipe.descriptionMarkdown)
            },
            preperationTime() {
                return this.recipe.prepTime.replace('PT', '').replace('M', ' Minutes')
            },
            calendarLink() {
                let event = {
                    title: 'Dinner tonight is: ' + this.recipe.name,
                    description: this.recipe.description,
                    start: dayjs().format('YYYY-MM-DD'),
                    location: window.location.href
                };

                return calendarLink.google(event);
            }
        },
        methods: {
            class_width(row) {
                let count = row.length;

                if (count >= 5) {
                    return 'w-full md:w-1/2 lg:w-1/5';
                } else if (count >= 4) {
                    return 'w-full md:w-1/2 lg:w-1/4';
                } else if( count === 3) {
                    return 'w-full md:w-1/2 lg:w-1/3';
                } else if( count === 2) {
                    return 'w-full md:w-1/2 lg:w-1/2';
                }

                return 'w-full';
            },
            md (text) {
                return markdown.render(text)
            }
        },
        mounted() {
            this.steps = array_chunks(this.recipe.steps, 2);
        }
    }
</script>
