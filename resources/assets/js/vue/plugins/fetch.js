import router from '~/vue/router'

let get = async (url) => {
    let response = await fetch('/admin' + url)
    return await response.json()
}

let getSync = async (url) => {
    let parameters = router.app.$route.query

    for (let parameter in parameters) {
        if (url[parameter] !== undefined) continue

        url[parameter] = parameters[parameter]
    }

    router.app.$router.push({
        path: router.app.$route.path,
        query: url.parameters(),
    })

    return await get(url.get())
}

let post = async (url, data) => {
    let body = new FormData()

    for (let prop in data) {
        body.append(prop, data[prop])
    }

    let response = await fetch('/admin' + url, {
        method: 'POST',
        headers: {
            'Accept': 'application/json'
        },
        body: body,
    })

    return await response.json()
}

export default {
    install(Vue) {
        Vue.prototype.$get = get
        Vue.prototype.$post = post
        Vue.prototype.$getSync = getSync
    }
}
