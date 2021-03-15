import Vue from 'vue'
import Vuex from 'vuex'

import axios from 'axios'

import router from '@/router/index'
Vue.use(Vuex)

import modal from './modal/index.module'

export default new Vuex.Store({
    state: {
        user_data: null,
        show_side_nav: true,
        mobile: false,
        links: [
            { name: 'Home', icon: 'home' },
            { name: 'Sign In', icon: 'dashboard' }
        ],
        error_msg: null,
        page_loading: false
    },
    modules: {
        modal: modal
    },
    getters: {
        USER_LINKS: state => {
            if (state.user_data) {
                return []
            } else {
                return state.links
            }
        },
        USER_DATA: state => state.user_data,
        SHOW_SIDE_NAV: state => state.show_side_nav,
        GET_ERR_MSG: state => state.error_msg
    },
    mutations: {
        SET_USER_DATA: (state, data) => {
            state.user_data = data
        },
        SHOW_SIDE_NAV: (state, val) => {
            if (!state.mobile && state.show_side_nav) {
                return 
            }
            state.show_side_nav = val && !!state.user_data
        },
        MOBILE: (state, val) => {
            state.mobile = val
        },
        SET_ERR_MSG: (state, val) => {
            state.error_msg = val
        },
        PAGE_LOADING: (state) => {
            state.page_loading = true
        },
        PAGE_LOADED: (state) => {
            state.page_loading = false
        }
    },
    actions: {
        SET_USER_DATA: ({ commit }, data) => {
            commit('SET_USER_DATA', data)
        },

        SIGN_OUT: ({ dispatch }, baseUrl) => {
            return new Promise((resolve) => {
                let url = baseUrl + '/Account/sign_out'
                dispatch('API_POST', { url })
                    .then(() => {
                        dispatch('SET_USER_DATA', null)
                        resolve()
                    })
            })
        },

        CHECK_SESSION: ({ dispatch, commit }, baseUrl) => {
            return new Promise((resolve, reject) => {
                let url = baseUrl + '/Account/check_session'
                dispatch('API_POST', { url })
                    .then(response => {
                        if (response.status) {
                            dispatch('SET_USER_DATA', response.data)
                            if (response.data.type === 1) { // vet
                                url = baseUrl + '/vet/Registration/get'
                                dispatch('API_POST', { url })
                                    .then(response => {
                                        if (response.data.registration_time) {
                                            if (response.data.expired) {
                                                commit('SET_ERR_MSG', 'Please pay your registration fee to renew your account')
                                            } else {
                                                commit('SET_ERR_MSG', null)
                                            }
                                        } else {
                                            commit('SET_ERR_MSG', 'Please pay your registration fee to activate your account')
                                        }
                                    })
                                    .catch(() => {
                                        commit('SET_ERR_MSG', 'Please pay your registration fee to activate your account')
                                    })
                            } else {
                                commit('SET_ERR_MSG', null)
                            }
                            resolve()
                        } else {
                            reject()
                        }
                    })
                    .catch(() => { 
                        dispatch('SET_USER_DATA', null)
                        reject() 
                    })
            })
        },

        API_POST: ( { dispatch }, { url, data, headers }) => {
            return new Promise((resolve, reject) => {
                data = data || {}
                headers = headers || {}
                
                axios.post(url, data, headers)
                    .then(response => { 
                        let payload = { response, resolve, reject }
                        dispatch('VALIDATE_API', payload)
                    })
                    .catch(error => { 
                        let payload = { error, reject }
                        dispatch('VALIDATE_ERROR', payload)
                    })
            })
        },

        API_GET: ({ dispatch }, { url, param }) => {
            return new Promise((resolve, reject) => {
                let apiGet = (param)
                    ? () => axios.get(url, { params: param })
                    : () => axios.get(url)

                apiGet()
                    .then(response => {
                        let payload = { response, resolve, reject }
                        dispatch('VALIDATE_API', payload)
                    })
                    .catch(error => {
                        let payload = { error, reject }
                        dispatch('VALIDATE_ERROR', payload)
                    })
            })
        },

        VALIDATE_API: ({ dispatch }, { response, resolve, reject }) => {
            if (response.status === 401) {
                dispatch('SET_USER_DATA', null)
                router.push({ name: 'Sign In'})
                return reject()
            }
            
            if (response.data.status === false) return reject(response.data.message)

            if (response.status !== 200 || 
                !response.data.hasOwnProperty('status') || 
                response.data.status === false ||
                response.data.status !== true){
                let payload = { error: response, reject }
                dispatch('VALIDATE_ERROR', payload)
            }
            // if (!response.data.hasOwnProperty('status')) return reject(response)
            // if (response.data.status !== true) return reject(response.data)

            return resolve(response.data)
        },

        VALIDATE_ERROR: ({ dispatch }, { error, reject }) => {
            if (error.response.status === 401) {
                dispatch('SET_USER_DATA', null)
                router.push({ name: 'Sign In'})
            }

            if (typeof error === 'string') {
                return reject(error) 
            }

            return reject('There was problem encountered while processing. Please try again.')
        }
    }
})
