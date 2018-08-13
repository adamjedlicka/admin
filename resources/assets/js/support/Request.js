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

    catch(error) {
        this._error = error

        return this
    }

    async _execute() {
        try {
            let response = await fetch(this._url.get(), {
                method: this._method,
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })

            let data = await response.json()

            this._success(data)
        } catch (e) {
            this._error(e)
        }
    }

}
