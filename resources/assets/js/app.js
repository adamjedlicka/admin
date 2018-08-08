import Vue from 'vue'
import router from '~/vue/router'
import '~/vue/plugins'
import '~/components'
import '~/helpers'

window.Vue = new Vue({
    el: '#app',
    router: router,
})
