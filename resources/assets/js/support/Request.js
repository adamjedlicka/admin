import router from '~/vue/router'

export default class Request {

    constructor(method, url, options = {}) {
        this._method = method
        this._url = url.prefix('/admin')
        this._options = options
    }

    syncQueryString() {
        let parameters = router.app.$route.query

        for (let parameter in parameters) {
            if (this._url[parameter] !== undefined) continue

            this._url[parameter] = parameters[parameter]
        }

        router.app.$router.push({
            path: router.app.$route.path,
            query: this._url.parameters(),
        })

        return this
    }

    then(success) {
        this._success = success

        this._execute()

        return this
    }

    async _execute() {
        let response = await fetch(this._url.get(), this.options)

        let data = await response.json()

        this._success(data)
    }

    get options() {
        let options = {
            method: this._method,
            headers: {
                'Accept': 'application/json',
            }
        }

        return this._mergeOptions(options, this._options)
    }

    _mergeOptions(to, from) {
        for (let attr in from) {
            if (typeof from[attr] === 'object') {
                to[attr] = this._mergeOptions(to[attr], from[attr])
            } else {
                to[attr] = from[attr]
            }
        }

        return to
    }
}
