export default {
    state: {
        logs: [
            {
              id: 1,
              content: 'Transitioned to Veg',
              target: 'seed-348afeba',
              href: '#',
              date: 'Sep 20',
              datetime: '2020-09-20',
              iconBackground: 'bg-gray-400',
            },
            {
              id: 2,
              content: 'Germinated',
              target: 'seed-a8b8f8c8',
              href: '#',
              date: 'Sep 22',
              datetime: '2020-09-22',
              iconBackground: 'bg-blue-500',
            },
            {
              id: 3,
              content: 'Harvested',
              target: 'seed-8b8f8c8a',
              href: '#',
              date: 'Sep 28',
              datetime: '2020-09-28',
              iconBackground: 'bg-green-500',
            },
            {
              id: 4,
              content: 'Germinated',
              target: 'seed-841f8c8a',
              href: '#',
              date: 'Sep 30',
              datetime: '2020-09-30',
              iconBackground: 'bg-blue-500',
            },
            {
              id: 5,
              content: 'Harvested',
              target: 'seed-1f8c8a',
              href: '#',
              date: 'Oct 4',
              datetime: '2020-10-04',
              iconBackground: 'bg-green-500',
            },
        ],
        pagination: {},
    },

    getters: {
        activityLogs: state => state.logs,
    },
    actions: {
        async fetchLogs({ state, commit }, options) {
            const { data: { data, ...pagination } } = await axios.get(buildUrl('/api/activity-logs', options));
            state.logs = data;
            state.pagination = pagination;
        }
    }
}