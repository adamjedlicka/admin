import Vue from 'vue'
import router from '~/router'

new Vue({
    el: '#app',
    router: router,

    render(createElement) {
        return createElement('router-view')
    },
})
