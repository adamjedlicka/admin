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
                    path: 'resources/:resource/create',
                    component: require('~/pages/resources/create'),
                },
                {
                    path: 'resources/:resource/:key',
                    component: require('~/pages/resources/detail'),
                },
                {
                    path: 'resources/:resource/:key/edit',
                    component: require('~/pages/resources/edit'),
                },
                {
                    path: 'resources/:resource/:key/attach/:relationship',
                    component: require('~/pages/resources/attach'),
                },
                {
                    path: 'relationships/:resource/:resourceKey/hasMany/:relationship/create',
                    component: require('~/pages/relationships/hasMany/create'),
                },
                {
                    path: 'relationships/:resource/:resourceKey/belongsToMany/:relationship/:relationshipKey/edit',
                    component: require('~/pages/relationships/belongsToMany/edit'),
                }
            ]
        },
    ]
})
