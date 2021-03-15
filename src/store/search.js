export default {
    namespaced: true,
    state: {
        list: {},
        totalList: 0,
        keyword: '',
        currentPage: 1
    },
    getters: {
        SEARCHED_VETS: state => state.list,
        CURRENT_PAGE: state => state.currentPage,
        SUBMITTED_KEYWORD: state => state.keyword,
        TOTAL_SEARCHED: state => state.totalList
    },
    mutations: {
        SEARCHED_VETS: (state, val) => {
            state.list = val
        },
        SUBMITTED_KEYWORD: (state, val) => {
            state.keyword = val
        },
        CURRENT_PAGE: (state, val) => {
            state.currentPage = val
        },
        TOTAL_SEARCHED: (state, val) => {
            state.totalList = val
        }
    },
    actions: {
        
    }
}