<template>
    <div class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded relative overflow-hidden">
        <div v-if="$store.getters.readingNotifications.includes(notification.id)" class="z-10 inset-0 absolute bg-gray-900/70">
            <span class="flex text-center items-center justify-center w-full h-full">Marking as read...</span>
        </div>
        <div v-if="notification.type === 'App\\Notifications\\GithubNotification'" class="flex flex-col gap-1">
            <a class="leading-tight" :href="notification.data.latest_comment_url.replace('api.', '').replace('/repos/','/')" target="_blank">{{ notification.data.title }}</a>
            <a class="text-xs text-gray-700 dark:text-gray-400" :href="'https://github.com/'+notification.data.full_name">{{ notification.data.full_name }}</a>
        </div>

        <div v-else>
            <div>{{notification.type}}</div>
            <pre>{{notification.data}}</pre>
        </div>
        <div class="flex justify-between">
            <button
                @click="() => markAsRead(notification.id)"
             class="text-xs">Mark as read</button>
            <div class="text-right text-xs text-gray-700 dark:text-gray-300">{{ date(notification.created_at)}}</div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['notification'],
    methods: {
        date(date) {
            return dayjs(date).format('MMM D, YYYY HH:mm:ss');
        },
        markAsRead(id) {
            this.$store.dispatch('markAsRead', id);
        }
    }
}
</script>