import router from '~/vue/router'

let get = async (url) => {
    let response = await fetch('/admin' + url)
    return await response.json()
}

let getSync = async (url) => {
    let parameters = router.app.$route.query

    for (let parameter in parameters) {
        if (url[parameter]) continue

        url[parameter] = parameters[parameter]
    }

    router.app.$router.push({
        path: router.app.$route.path,
        query: url.parameters(),
    })

    return await get(url.get())
}

export default {
    install(Vue) {
        Vue.prototype.$get = get
        Vue.prototype.$getSync = getSync
    }
}
