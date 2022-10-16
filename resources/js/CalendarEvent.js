import { RRule } from "rrule";

export default class CalendarEvent {
    constructor(calendarEvent) {
        this.calendarEvent = calendarEvent;
        this.instanceDate = null;
    }

    setDate(date) {
        this.instanceDate = date;
    }

    nextOccurrences(start, end) {
        const definition = {
            ...(this.calendarEvent?.interval ? { interval: this.calendarEvent?.interval } : {}),
            ...(this.calendarEvent?.frequency ? { freq: this.getCompatibleFrequency(this.calendarEvent?.frequency) } : {}),
            ...(this.calendarEvent?.weekday_start ? { wkst: this.calendarEvent?.weekday_start || undefined } : {}),
            ...(this.calendarEvent?.number_of_occurrences ? { count: this.calendarEvent?.number_of_occurrences } : {}),
            ...(this.calendarEvent?.for_months ? { bymonth: this.calendarEvent?.for_months } : {}),
            ...(this.calendarEvent?.for_week_numbers ? {byweekno: this.calendarEvent?.for_week_numbers } : {}),
            ...(this.calendarEvent?.for_year_day ? { byyearday: this.calendarEvent?.for_year_day } : {}),
            ...(this.calendarEvent?.for_month_day ? { bymonthday: this.calendarEvent?.for_month_day } : {}),
            ...(this.calendarEvent?.for_hour ? { byhour: this.calendarEvent?.for_hour } : {}),
            ...(this.calendarEvent?.for_minute ? { byminute: this.calendarEvent?.for_minute } : {}),
            ...(this.calendarEvent?.for_second ? { bysecond: this.calendarEvent?.for_second } : {}),
            ...(this.calendarEvent?.for_set_position ? { bysetpos: this.calendarEvent?.for_set_position } : {}),
            ...(this.calendarEvent?.date_start ? { dtstart: new Date(this.calendarEvent?.date_start) } : {}),
            // dtend: this.calendarEvent?.date_end ? new Date(this.calendarEvent?.date_end) : undefined,
        };

        const t = Object.keys(definition)
            .filter(key => definition[key])
            .reduce((obj, key) => ({
                ...obj,
                [key]: definition[key],
            }), {})

        delete t.bysetpos; 
        const rule = new RRule(t);
        //
        // // return rule;
        return rule.between(start.$d, end.$d)
    }

    getCompatibleFrequency(frequency) {
        switch (frequency) {
            case 'YEARLY':
                return RRule.YEARLY;
            case 'MONTHLY':
                return RRule.MONTHLY;
            case 'WEEKLY':
                return RRule.WEEKLY;
            case 'DAILY':
                return RRule.DAILY;
            case 'HOURLY':
                return RRule.HOURLY;
            case 'MINUTELY':
                return RRule.MINUTELY;
            case 'SECONDLY':
                return RRule.SECONDLY;
        }
    }
}
