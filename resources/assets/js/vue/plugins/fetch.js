export default {
    async get(url) {
        let response = await fetch('/admin' + url)
        return await response.json()
    },

    install(Vue, options) {
        Vue.prototype.$get = this.get
    }
}
