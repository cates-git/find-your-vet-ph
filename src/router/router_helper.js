import store from '@/store/index'
/** register your module to the main store @/store/store
 *
 * @param {string} STORE_KEY - name of your module
 * @param {store} module - the vuex store you want to import
 *
 * @return void
 */
export function registerStore (STORE_KEY, module) {
    if (!(STORE_KEY in store._modules.root._children)) {
        store.registerModule(STORE_KEY, module)
    }
}
