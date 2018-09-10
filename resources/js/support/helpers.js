window.locale = function () {
    return document.getElementsByTagName("html")[0].getAttribute("lang")
}

window.clone = function (obj) {
    // Handle the 3 simple types, and null or undefined
    if (null == obj || "object" != typeof obj) return obj

    // Handle Date
    if (obj instanceof Date) {
        var copy = new Date()
        copy.setTime(obj.getTime())
        return copy
    }

    // Handle Array
    if (obj instanceof Array) {
        var copy = []
        for (var i = 0, len = obj.length; i < len; i++) {
            copy[i] = clone(obj[i])
        }
        return copy
    }

    // Handle Object
    if (obj instanceof Object) {
        var copy = {}
        for (var attr in obj) {
            if (obj.hasOwnProperty(attr)) copy[attr] = clone(obj[attr])
        }
        return copy
    }

    throw new Error("Unable to copy obj! Its type isn't supported.")
}

window.delay = function (timeout) {
    return new Promise(resolve => {
        setTimeout(resolve, timeout)
    })
}

window.toQueryString = function (parameters) {
    let query = []

    for (let parameter in parameters) {
        query.push(`${parameter}=${parameters[parameter]}`)
    }

    return query.join('&')
}
