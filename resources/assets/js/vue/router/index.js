import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

export default new VueRouter({
    mode: 'history',
    base: '/admin',
    routes: [
        {
            path: '/',
            component: require('~/views/HomeView'),
            children: [
                {
                    path: 'resources/:resource',
                    component: require('~/views/ResourceIndexView'),
                },
                {
                    path: 'resources/:resource/:id',
                    component: require('~/views/ResourceDetailView'),
                },
                {
                    path: 'resources/:resource/:id/edit',
                    component: require('~/views/ResourceEditView'),
                }
            ]
        },
    ]
})
