import Request from '~/support/Request'
import router from '~/vue/router'
import Url from '~/support/Url'

let get = (url) => {
    if (typeof url === 'string') {
        url = new Url(url)
    }

    return new Request('GET', url)
}


let post = (url, data) => {
    if (typeof url === 'string') {
        url = new Url(url)
    }

    return new Request('POST', url, {
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
}

let put = (url, data) => {
    if (typeof url === 'string') {
        url = new Url(url)
    }

    return new Request('PUT', url, {
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
}

let del = (url) => {
    if (typeof url === 'string') {
        url = new Url(url)
    }

    return new Request('DELETE', url)
}

Request.intercept((response, data) => {
    if (response.status == 500) {
        toast(data.message).error()
    }
})

Request.intercept((response, data) => {
    if (response.status == 403) {
        router.replace('/403')
    }
})

Request.intercept((response, data) => {
    if (response.status == 404) {
        router.replace('/404')
    }
})

export default {
    install(Vue) {
        Vue.prototype.$get = get
        Vue.prototype.$post = post
        Vue.prototype.$put = put
        Vue.prototype.$delete = del
    }
}
