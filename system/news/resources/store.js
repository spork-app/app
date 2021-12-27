import dayjs from "dayjs";

export default {
    state: {
        articles: [],
        readArticles: [],
    },
    getters: {
        articles: state => state.articles.filter(article => !state.readArticles.map(({id}) => id).includes(article.id)),
        readArticles: state => state.readArticles.map,
    },
    actions: {
        getArticles({state, commit}) {
            // Filter out articles that were read more than 4 days ago.
            state.readArticles = (JSON.parse(localStorage.getItem('readArticles')) ?? []).filter(({ date }) => dayjs(date).isAfter(dayjs().subtract(4, 'days')));
            
            axios.get('/api/news')
                .then(response => {
                    commit('setArticles', response.data);
                })
                .catch(error => {
                    console.log(error);
                });
        },
        markAsRead({state}, {id}) {
            state.readArticles.push({id, date: new Date()});
            localStorage.setItem('readArticles', JSON.stringify(state.readArticles));
        },
    },
    mutations: {
        setArticles(state, articles) {
            state.articles = articles;
        }
    }
};