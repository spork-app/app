import dayjs from 'dayjs'
import CalendarEvent from './CalendarEvent'
import { buildUrl } from '@kbco/query-builder';
import Echo from 'laravel-echo';

import '@system/core/resources/app';

dayjs.extend(require('dayjs/plugin/utc'))
dayjs.extend(require('dayjs/plugin/localizedFormat'));
dayjs.extend(require('dayjs/plugin/relativeTime'));
dayjs.extend(require('dayjs/plugin/isToday'));

window.buildUrl = buildUrl;
window.CalendarEvent = CalendarEvent;

window.dayjs = dayjs;
window._ = require('lodash');
Array.prototype.unique = function() {
    return [... new Set(this)];
}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');
window.axios.defaults.withCredentials = true;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.axios.interceptors.request.use(function (config) {
    return config;
}, function (error) {
    const status = error?.response?.status
    if (status === 401) {
        window.location = "/login"
        return Promise.reject(error);
    }

    if (status === 403) {
        Spork.toast('You do not have permission to perform this action.', 'error')
    }

    if (status === 404) {
        Spork.toast('The requested resource was not found.', 'error')
    }

    return Promise.reject(error);
});

window.Form = class Form {
    constructor(body) {
        this.errors = {}

        for (let part in body) {
            this[part] = body[part];
        }
    }
    setErrors(errors = {}) {
        this.errors = errors;
    }
    hasErrors(key) {
        return this.errors[key] !== undefined;
    }
    error(key) {
        return this.errors[key][0];
    }
};

window.createArray = (items) => {
    let l = [];
    for (let i = 0; i < items; i ++) l.push(i + 1)
    return l;
};

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    forceTLS: false,
    wsHost: window.location.host.includes('githubpreview.dev') ? window.location.host + '-6001' : window.location.host,
    wsPort: window.location.host.includes('githubpreview.dev') ? 80 : 6001,
    encrypted: false
});
