<template>
    <div  class="flex flex-wrap justify-center p-4 w-full h-screen overflow-y-scroll">
        <div class="flow-root w-full">  
            <div>
                <select name="category" id="category">
                    <option value=""></option>
                    <option value="business">Business</option>
                    <option value="entertainment">Entertainment</option>
                    <option value="general">General</option>
                    <option value="health">Health</option>
                    <option value="science">Science</option>
                    <option value="sports">Sports</option>
                    <option value="technology">Technology</option>
                </select>
            </div>
            <!-- <ul role="list" class="divide-y divide-gray-200">
                <li v-for="message in $store.getters.articles" :key="message.id" class="relative bg-white py-5 px-4 hover:bg-gray-50 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600">
                <div class="flex justify-between space-x-3">
                    <div class="min-w-0 flex-1">
                    <div href="#" class="block focus:outline-none">
                        <span class="absolute inset-0" aria-hidden="true" />
                        <p class="text-sm font-medium text-gray-900 truncate">{{ message.title }}</p>
                        <p class="text-sm text-gray-500 truncate">{{ message?.source?.name }}</p>
                    </div>
                    </div>
                    <time :datetime="message.datetime" class="flex-shrink-0 whitespace-nowrap text-sm text-gray-500">{{ dayjs(message.publishedAt).from(dayjs()) }}</time>
                </div>
                <div class="mt-1">
                    <p class="line-clamp-2 text-sm text-gray-600">
                    {{ message.description }}
                    </p>
                    <button @click="() => markAsRead(event)" class="text-red-500 hover:text-red-700 font-bold rounded text-xs mt-2 focus:border-red-500 focus:border">
                        Marked as read
                    </button> 
                </div>
                </li>
            </ul> -->
            <div role="list" class="w-full grid grid-cols-3 gap-4 ">
                <div v-for="(event, eventIdx) in $store.getters.articles" :key="eventIdx">
                    <div class="rounded overflow-hidden shadow bg-white dark:bg-gray-600">
                        <div class="bg-gray-900">
                            <img class="h-48 mx-auto" :src="event.urlToImage" :alt="event.name" />
                        </div>
                        <div class="px-4 my-2">
                            <div class="font-bold text-xl mb-2">
                                <a @click="() => delayMarkAsRead(event)"  target="_blank" :href="event.url">{{ event.title }}</a>
                                <div class="text-xs text-gray-400 dark:text-gray-300 mt-2 ">{{ dayjs(event.publishedAt).from(dayjs()) }}</div>
                            </div>
                            <p class="text-base">
                                {{ event.description}}
                            </p>
                        </div>
                        <div class="p-2 w-full flex justify-between text-xs">
                            <span class="inline-block bg-gray-100 dark:bg-gray-500 rounded-full px-3 py-1  font-semibold text-gray-600 dark:text-gray-200">
                                #{{event.source.name.toLowerCase().replace(' ','-')}}
                            </span>

                            
                            <button @click="() => markAsRead(event)" class="text-red-500 hover:text-red-700  font-bold px-2 rounded">
                                Marked as read
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    components: {},
    name: "News",
    data() {
        return {
            dayjs
        }
    },

    methods: {
        markAsRead(article) {
            this.$store.dispatch('markAsRead', article)
        },
        delayMarkAsRead(article) {
            setTimeout(() => {
                this.markAsRead(article)
            }, 100)
        },
        getNews() {
            this.$store.dispatch('getArticles')
        }
    },

    mounted() {
        this.getNews()
    }
}
</script>
