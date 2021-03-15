import '@babel/polyfill'
import Vue from 'vue'
import './plugins/axios'
import './plugins/vuetify'
import App from './App.vue'
import router from './router/index'
import store from './store/index'
import 'roboto-fontface/css/roboto/roboto-fontface.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import constants from './constants'
import '@/components/global_components'

Vue.config.productionTip = false

// backend
Vue.prototype.$baseUrl = constants.baseUrl
Vue.prototype.$app = constants.app
Vue.prototype.$userTypes = constants.userTypes

Vue.prototype.$theme = {
    dark: true,
    background: 'light-blue',
    content_background: 'grey',
    text: 'light-blue--text',
    dark_text: 'white--text'
}
/**
 * CHECK SESSION IN EVERY ROUTE
 */
router.beforeEach((to, from, next) => {
    if (!to.name) {
        next({ name: 'Home' })
    }
    if (to.name == 'Sign In' || 
        to.name == 'Sign Up' || 
        to.name == 'Home') {
        next()
        return
    }
    store.commit('PAGE_LOADING')
    store.dispatch('CHECK_SESSION', constants.baseUrl)
        .then(() => {
            if (to.name == 'Sign In' || 
                to.name == 'Sign Up' || 
                to.name == 'Home' ||
                !to.name) {
                next({ name: 'Profile' }) 
            } else {
                next()
            }
        })
        .catch(() => { 
            // if (!to.name) {
            //     next({ name: 'Home' })
            // } else if (to.name == 'Sign In' || 
            //     to.name == 'Sign Up' || 
            //     to.name == 'Home') {
            //     next()
            // } else {
            //     next({ name: 'Sign In' }) 
            // }
            next({ name: 'Home' }) 
        })
        .finally(() => { store.commit('PAGE_LOADED') })
})

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app')
