import getters from './getters'
import mutations from './mutations'
import actions from './actions'

const state = {
    SHOW_MODAL: false,
    SHOW_MODAL_LOADING: false,
    SHOW_MODAL_ALERT: false,
    SHOW_MODAL_CONFIRM: false,
    body: 'Modal body',
    bg_header: 'info'
}

export default {
    state: state,
    getters: getters,
    mutations: mutations,
    actions: actions
}
