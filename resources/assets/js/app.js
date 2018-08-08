import Vue from 'vue'
import router from '~/router'

import fetch from '~/vue/plugins/fetch'
Vue.use(fetch)

new Vue({
    el: '#app',
    router: router,
})
