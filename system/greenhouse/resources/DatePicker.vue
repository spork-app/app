<template>
    <div class="mb-5 w-64 text-gray-800 dark:text-gray-100" :style="showDatepicker ?'height: 340px' : 'height: 3rem;'">

        <label class="font-medium uppercase text-sm mb-1 block">{{label || 'Select a date'}}</label>
        <div class="relative">
            <div @click="() => showDatepicker = true" class="w-full">
                <input
                    type="text"
                    readonly
                    v-model="datepickerValue"
                    @keydown.escape="() => showDatepicker = false"
                    class="w-full pl-4 pr-10 py-3 leading-none rounded-lg shadow-sm focus:outline-none focus:shadow-outline font-medium dark:bg-gray-500"
                    placeholder="Select date"
                    style="width: 17rem;"
                >

                <div class="absolute top-0 right-0 px-3 py-2">
                    <svg class="h-6 w-6 text-gray-400"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>

            <div
                class="mt-12 rounded-lg shadow p-4 absolute top-0 left-0 bg-gray-50 dark:bg-gray-500"
                style="width: 17rem"
                v-show="showDatepicker">

                <div class="flex justify-between items-center mb-2">
                    <div>
                        <span v-text="displayDate.format('MMMM')" class="text-lg font-bold"></span>
                        <span v-text="displayDate.year()" class="ml-1 text-lg font-normal"></span>
                    </div>
                    <div>
                        <button
                            type="button"
                            class="transition ease-in-out duration-100 inline-flex cursor-pointer p-1 rounded-full"
                            @click="goBackAMonth">
                            <svg class="h-6 w-6 inline-flex"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button
                            type="button"
                            class="transition ease-in-out duration-100 inline-flex cursor-pointer p-1 rounded-full"
                            @click="goForwardAMonth">
                            <svg class="h-6 w-6 inline-flex"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex flex-wrap mb-3 -mx-1">
                    <div v-for="(day, index) in days" :key="index" class="flex-1">
                        <div class="px-1" @click="() => chooseDate(day)">
                            <div
                                v-text="day"
                                class="font-medium text-center text-xs"></div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap -mx-1">
                    <div v-for="blankday in blankdays" :key="'blankday' + blankday" style="width: 14.28%"
                    >
                        <div
                            class="text-center border p-1 border-transparent text-sm"
                        ></div>
                    </div>
                    <div v-for="(date, dateIndex) in no_of_days" :key="dateIndex" style="width: 14.28%">
                        <div class="px-1 mb-1">
                            <div
                                @click="getDateValue(date)"
                                v-text="date"
                                class="cursor-pointer text-center text-sm leading-none rounded-full leading-loose transition ease-in-out duration-100 target:bg-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700"
                                :class="{
                                    'underline': isToday(date) === true,
                                    'border-blue-500 border text-blue-600': isSelected(date) === true,
                                }"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
    export default {
        props: ['id', 'modelValue', 'label'],
        data() {
            return {
                showDatepicker: false,
                datepickerValue:  'No date selected',
                displayDate: dayjs(),
                month: dayjs().month(),
                year: dayjs().year(),
                no_of_days: [],
                blankdays: [1,2,3,4],
                days: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                months: [
                    "January",
                    "February",
                    "March",
                    "April",
                    "May",
                    "June",
                    "July",
                    "August",
                    "September",
                    "October",
                    "November",
                    "December"
                ],
                actualValue: '',
            };
        },
        computed: {
            realMonth() {
                return Number(this.month) + 1;
            }
        },
        methods: {
            goBackAMonth() {
                const value = this.displayDate ?? dayjs();

                this.displayDate = value.subtract(1, 'month');
                this.getNoOfDays();
            },
            goForwardAMonth() {
                const value = this.displayDate ?? dayjs();
                this.displayDate = value.add(1, 'month')
                this.getNoOfDays();
            },

            isToday(date) {
                const today = dayjs();
                const d = new Date(this.displayDate.year(), this.displayDate.month(), date);
                return today.$d.toDateString() === d.toDateString() ? true : false;
            },
            isSelected(date) {
                const today = this.modelValue;

                if (!today) {
                    return false;
                }

                return today.$d.toDateString() === new Date(this.displayDate.year(), this.displayDate.month(), date).toDateString();
            },

            getDateValue(date) {
                let selectedDate = new Date(this.displayDate.year(), this.displayDate.month(), date);
                this.datepickerValue = selectedDate.toDateString();

                this.chooseDate(selectedDate.toDateString());
                this.showDatepicker = false;
            },

            getNoOfDays() {
                let daysInMonth = this.displayDate.daysInMonth();

                // find where to start calendar day of week
                let dayOfWeek = this.displayDate.$d.getDay();
                let blankdaysArray = [];
                for (var i = 1; i <= dayOfWeek; i++) {
                    blankdaysArray.push(i);
                }

                let daysArray = [];
                for (var i = 1; i <= daysInMonth; i++) {
                    daysArray.push(i);
                }

                this.blankdays = blankdaysArray;
                this.no_of_days = daysArray;
            },
            chooseDate(day) {
                const value = dayjs(day);

                this.update(value);
            },
            update(date) {
                this.$emit('update:modelValue', date);
            }
        },
        mounted() {
            this.getNoOfDays()
        }
    };
</script>