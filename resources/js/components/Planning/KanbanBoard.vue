<template>
    <div v-if="!loading" class="p-2 flex flex-wrap w-full gap-4">
        <!-- Thank you https://github.com/messerli90/laravel-vue-kanban-tutorial :D -->
        <div class="w-full rounded-lg">
            <div class="text-3xl font-medium text-gray-600">Planning</div>
        </div>

        <div
            v-for="status in statuses"
            :key="status.slug"
            class="w-4/5 max-w-xs "
        >
            <div class="rounded-md shadow-md text-gray-600 bg-gray-200 overflow-hidden">
                <div class="p-3 flex justify-between items-baseline">
                    <div class="font-medium">
                        {{ status.title }}
                    </div>
                    <button
                        @click="openAddTaskForm(status.id)"
                        class="border border-blue-500 py-1 px-2 text-sm hover:underline rounded text-blue-500"
                    >
                        Add Task
                    </button>
                </div>
                <div class="p-2 bg-white">
                    <!-- Tasks -->
                    <draggable
                        class="flex-1 gap-2 flex flex-col list-group"
                        v-model="status.tasks"
                        v-bind="taskDragOptions"
                        @end="handleTaskMoved"
                        item-key="title"
                        style="min-height:1rem;"
                    >
                        <template #header>
                            <AddTaskForm
                                v-if="newTaskForStatus === status.id"
                                :status-id="status.id"
                                v-on:task-added="handleTaskAdded"
                                v-on:task-canceled="closeAddTaskForm"
                            />
                        </template>
                        <template #item="{ element: task }">
                            <transition-group
                                tag="div"
                            >
                                <div
                                    :key="task.id"
                                    class="p-4 flex flex-col bg-white rounded-md border border-dashed border-gray-300 rounded transform hover:shadow-md cursor-pointer r"
                                    @contextmenu.prevent="(e) => openContextMenu(e, task)"
                                >
                                    <span class="block mb-2 text-xl text-gray-900">
                                      {{ task.title }}
                                    </span>
                                    <p class="text-gray-700">
                                        {{ task.description }}
                                    </p>
                                    <div class="mt-4 flex justify-between w-full">
                                        <div class="font-bold">
                                            #{{ task.id }}
                                        </div>

                                        <div class="w-6 h-6 overflow-hidden rounded-full flex items-center justify-center" v-if="task.assignee">
                                            <img :src="'/storage/' + task.assignee.profile_photo" alt="">
                                        </div>
                                    </div>
                                </div>
                                <!-- ./Tasks -->
                            </transition-group>
                        </template>
                    </draggable>
                </div>
            </div>

        </div>
        <div id="div-context-menu" class="absolute z-10 bg-white rounded shadow-lg p-2" style="top: -9999px; left: -9999px;">
            <div class="w-full flex flex-col">
                <div class="uppercase text-gray-600 text-xs">assign user</div>
                <div class="flex flex-col text-sm pt-2">
                    <button v-for="user in users" :key="'user.' + user.id" @click.prevent="() => assignUser(user)">{{ user.name }}</button>

                    <div v-if="users.length === 0">No Users</div>
                </div>
            </div>
        </div>
        <button v-if="display_background" class="fixed w-full h-full bg-gray-800 opacity-25 top-0 left-0 right-0 bottom-0" @click="display_background = false"></button>
        <!-- ./Columns -->
    </div>
    <div v-else class="p-4 rounded-lg bg-white text-lg">
        Loading...
    </div>
</template>

<script>
import draggable from "vuedraggable";
import AddTaskForm from "./AddTaskForm";
import { ref } from 'vue';

export default {
    components: { draggable, AddTaskForm },
    props: {
        initialData: Array
    },
    setup() {
        return {
            statuses: ref([]),
            users: ref([]),
            newTaskForStatus: ref(0),
            loading: ref(true),
            targetTask: ref(null),
            popoverX: ref(0),
            popoverY: ref(0),
            assigningUser: ref(false),
            display_background: ref(false),
        };
    },
    computed: {
        taskDragOptions() {
            return {
                animation: 200,
                group: "task-list",
                dragClass: "status-drag"
            };
        }
    },
    mounted() {
        this.getTasks();
        this.getUsers();
    },
    watch: {
        popoverY() {
            var menu = document.getElementById("div-context-menu");
            menu.style.left = this.popoverX + 'px'
            menu.style.top = this.popoverY + 'px'
            menu.style.display = 'block'
        },
        display_background(newValue, oldValue) {
            if (oldValue) {
                this.popoverX = -99999;
                this.popoverY = -99999;
            }
        }
    },
    methods: {
        assignUser(user) {
            this.assigningUser = true;
            axios.post('/api/assign-task', {
                task_id: this.targetTask.id,
                user_id: user.id,
            }).then((res) => {
                this.assigningUser = false;
            })
        },
        // When you right click
        openContextMenu(e, task) {
            e.preventDefault();
            this.popoverX = e.pageX;
            this.popoverY = e.pageY
            this.targetTask = task;
            this.display_background = true;
        },


        getTasks() {
            this.loading = true;
            axios.get('/api/status?include=tasks.creator,tasks.assignee')
                .then((res) => {
                    this.statuses = res.data.sort((a, b) => a.order > b.order ? 1 : -1);
                    this.loading = false;
                })
        },
        getUsers() {
            axios.get('/api/users')
            .then((res) => {
                this.users = res.data;
            })
        },
        openAddTaskForm(statusId) {
            this.newTaskForStatus = statusId;
        },
        closeAddTaskForm() {
            this.newTaskForStatus = 0;
        },
        handleTaskAdded(newTask) {
            // Find the index of the status where we should add the task
            const statusIndex = this.statuses.findIndex(
                status => status.id === newTask.status_id
            );
            // Add newly created task to our column
            this.statuses[statusIndex].tasks.push(newTask);
            // Reset and close the AddTaskForm
            this.closeAddTaskForm();
            this.getTasks();
        },
        handleTaskMoved(evt) {
            axios.put("/api/sync", { columns: this.statuses }).catch(err => {
                console.log(err.response);
            });
        }
    }
};
</script>

<style scoped>
.status-drag {
    transition: transform 0.5s;
    transition-property: all;
}
.list-group:empty {
    padding:1rem;
    text-align:center;
    border: dashed 1px #83a2e3;
    border-radius: .5rem;
}

.list-group:empty:before {
    content: 'No tasks yet';
    font-style: italic;
}
</style>
