import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

export default new VueRouter({
    mode: 'history',
    base: '/admin',
    routes: [
        {
            path: '/',
            component: require('~/layouts/default'),
            children: [
                {
                    path: 'resources/:resource',
                    component: require('~/pages/resources/index'),
                },
                {
                    path: 'resources/:resource/:id',
                    component: require('~/pages/resources/detail'),
                },
                {
                    path: 'resources/:resource/:id/edit',
                    component: require('~/pages/resources/edit'),
                }
            ]
        },
    ]
})
