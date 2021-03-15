export default {
    SHOW_LOADING ({ commit }) {
        commit('SHOW_MODAL_LOADING', true)
    },
    CLOSE_LOADING ({ commit }) {
        commit('SHOW_MODAL_LOADING', false)
    },
    SHOW_ALERT ({ commit }, message) {
        commit('SET_HEAD_COLOR', 'info')
        commit('SHOW_MODAL_ALERT', { show: true, msg: message })
    },
    CLOSE_ALERT ({ commit }) {
        commit('SHOW_MODAL_ALERT', { show: false, msg: null })
    },
    SHOW_CONFIRM ({ commit }, message) {
        commit('SET_MESSAGE', message)
        commit('SHOW_MODAL_CONFIRM', true)
    },
    CLOSE_CONFIRM ({ commit }) {
        commit('SET_MESSAGE', null)
        commit('SHOW_MODAL_CONFIRM', false)
    },
    SHOW_SUCCESS ({ commit }, message) {
        commit('SET_HEAD_COLOR', 'success')
        commit('SHOW_MODAL_ALERT', { show: true, msg: message })
    },
    CLOSE_SUCCESS ({ commit }) {
        commit('SHOW_MODAL_ALERT', { show: false, msg: null })
    },
    SHOW_ERROR ({ commit }, message) {
        commit('SET_HEAD_COLOR', 'error')
        commit('SHOW_MODAL_ALERT', { show: true, msg: message })
    },
    CLOSE_ERROR ({ commit }) {
        commit('SHOW_MODAL_ALERT', { show: false, msg: null })
    }
}
