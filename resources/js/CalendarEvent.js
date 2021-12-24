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
            interval: this.calendarEvent?.interval,
            freq: this.getCompatibleFrequency(this.calendarEvent?.frequency),
            wkst: this.calendarEvent?.weekday_start || undefined,
            count: this.calendarEvent?.number_of_occurrences || undefined,
            bymonth: this.calendarEvent?.for_months?.split(','),
            byweekno: this.calendarEvent?.for_week_numbers?.split(','),
            byyearday: this.calendarEvent?.for_year_day?.split(','),
            bymonthday: this.calendarEvent?.for_month_day?.split(','),
            byhour: this.calendarEvent?.for_hour?.split(','),
            byminute: this.calendarEvent?.for_minute?.split(','),
            bysecond: this.calendarEvent?.for_second?.split(','),
            bysetpos: this.calendarEvent?.for_set_position?.split(','),
            dtstart: this.calendarEvent?.date_start ? new Date(this.calendarEvent?.date_start) : undefined,
            // dtend: this.calendarEvent?.date_end ? new Date(this.calendarEvent?.date_end) : undefined,
        };

        const t = Object.keys(definition)
            .filter(key => definition[key])
            .reduce((obj, key) => ({
                ...obj,
                [key]: definition[key],
            }), {})
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
