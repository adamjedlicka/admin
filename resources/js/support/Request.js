import router from '~/vue/router'

let interceptors = []

export default class Request {

    constructor(method, url, options = {}) {
        this._method = method
        this._url = url.prefix('/luna')
        this._options = options
    }

    /**
     * @param {string} prefix
     */
    syncQueryString(prefix = '') {
        if (prefix != '') {
            prefix += '.'
        }

        // Get parameters from the URL barl
        let parameters = router.app.$route.query

        // Save every parameter that starts with prefix in current query string of this request
        for (let parameter in parameters) {
            if (!parameter.startsWith(prefix)) continue

            let woPrefix = parameter.substring(prefix.length)

            if (this._url[woPrefix] !== undefined) continue

            this._url[woPrefix] = parameters[parameter]
        }

        // Now we create new query string for url bar
        let query = {}

        // Preserve all parameters except for current request
        for (let parameter in parameters) {
            if (parameter.startsWith(prefix)) continue

            query[parameter] = parameters[parameter]
        }

        // Store parameters of current request in the URL bar
        for (let parameter in this._url.parameters()) {
            query[prefix + parameter] = this._url[parameter]
        }

        router.app.$router.push({
            path: router.app.$route.path,
            query: query,
        })

        return this
    }

    then(success) {
        this._success = success

        this._execute()

        return this
    }

    static intercept(callback) {
        interceptors.push(callback)
    }

    async _execute() {
        let response = await fetch(this._url.get(), this.options)

        let data = await response.json()

        interceptors.forEach(it => it(response, data))

        this._success(data)
    }

    get options() {
        let options = {
            method: this._method,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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
