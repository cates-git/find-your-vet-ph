export default {
    SHOW_MODAL_LOADING: (state, val) => {
        state.SHOW_MODAL_LOADING = val
    },
    SHOW_MODAL_ALERT: (state, { show, msg }) => {
        state.SHOW_MODAL_ALERT = show
        state.body = msg
    },
    SHOW_MODAL_CONFIRM: (state, val) => {
        state.SHOW_MODAL_CONFIRM = val
    },
    SET_MESSAGE: (state, message) => {
        state.body = message
    },
    SET_HEAD_COLOR: (state, color) => {
        state.bg_header = color
    }
}
