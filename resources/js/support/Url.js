export default class Url {

    constructor(url) {
        this._url = url || this._initUrl()
    }

    get() {
        let params = this.queryString()

        if (params.length == 0) {
            return this.url()
        }

        return this.url() + '?' + params
    }

    url() {
        return this._prefix + this._url
    }

    parameters() {
        let parameters = {}

        for (let parameter in this) {
            if (this._shouldSend(parameter)) {
                parameters[parameter] = this[parameter]
            }
        }

        return parameters
    }

    queryString() {
        return Object.keys(this)
            .filter(key => this._shouldSend(key))
            .map(key => this._encode(key))
            .filter(param => param.length > 0)
            .join('&')
    }

    object(name) {
        let object = {}

        for (let parameter in this.parameters()) {
            if (!parameter.startsWith(name)) continue

            let accessor = parameter.split('.')
            object[accessor[1]] = this[parameter]
        }

        return object
    }

    prefix(prefix) {
        this._prefix = prefix

        return this
    }

    _initUrl() {
        let loc = window.location

        loc.search.substr(1)
            .split('&')
            .forEach(param => {
                param = param.split('=')
                this[param[0]] = decodeURIComponent(param[1])
            })

        return loc.pathname
    }

    _encode(key) {
        if (this[key] instanceof Array) {
            return Object.keys(this[key])
                .map(_key => `${key}[${_key}]=${this[key][_key]}`)
                .join('&')
        } else {
            return `${key}=${this[key]}`
        }
    }

    _shouldSend(key) {
        if (key[0] == '_') return false

        if (this[key] instanceof Array) {
            return Object.keys(this[key]).length > 0
        } else {
            return this[key] != null
        }
    }
}
