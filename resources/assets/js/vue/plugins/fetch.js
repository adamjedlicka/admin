import Request from '~/support/Request'
import Url from '~/support/Url'

let get = (url) => {
    if (typeof url === 'string') {
        url = new Url(url)
    }

    return new Request('GET', url)
}


let post = async (url, data) => {
    let response = await fetch('/admin' + url, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })

    return await response.json()
}

let put = async (url, data) => {
    let response = await fetch('/admin' + url, {
        method: 'PUT',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })

    return await response.json()
}

let del = async (url) => {
    let response = await fetch('/admin' + url, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json'
        },
    })

    return await response.json()
}

export default {
    install(Vue) {
        Vue.prototype.$get = get
        Vue.prototype.$post = post
        Vue.prototype.$put = put
        Vue.prototype.$delete = del
    }
}
