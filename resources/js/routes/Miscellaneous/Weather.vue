<template>
<div  class="w-full flex flex-wrap justify-center">
    <div class="flex flex-col">
        <div v-for="day in forecast" :key="day.date" class="font-medium flex flex-wrap tracking-wide leading-loose rounded flex flex-wrap items-center justify-between p-2">
            <div class="flex-shrink flex flex-col justify-center items-center text-center">
                <div class="w-10 text-xl">{{ dayjs(day.updated_at).format('MMM') }}</div>
                <div class="w-10 font-thin text-3xl">{{ dayjs(day.updated_at).format('DD') }}</div>
            </div>
            <div class="flex flex-grow items-center">
                <img class="w-24 h-24 fill-current" :src="day.condition_image" alt="Alt"/>
                <div class="flex items-center justify-center ml-4">
                    <div class="text-5xl font-thin">{{ day.temperature }}°F</div>
                    <div class="flex flex-col ml-2">
                        <div class="leading-none text-base font-thin">Humidity: {{ day.humidity }}%</div>
                        <div class="leading-none text-base font-thin">Cloud Cover: {{ day.cloud_cover }}%</div>
                        <div class="leading-none text-base font-thin">Wind: {{ day.wind_speed }} MPH</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</template>

<script>
export default {
    name: "Weather",
    data() {
        return {
            forecast: [],
            dayjs
        }
    },

    mounted() {
        axios.get('/api/weather').then(({ data }) => {
            this.forecast = data
        })
    }
}
</script>
