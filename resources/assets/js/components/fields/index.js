import Vue from 'vue'

let registerComponent = (component) => {
    Vue.component(`${component}-index-field`, require(`./index/${component}`))
    Vue.component(`${component}-detail-field`, require(`./detail/${component}`))
    Vue.component(`${component}-edit-field`, require(`./edit/${component}`))
}

let components = [
    'ID',
    'Text',

    'BelongsTo',
    'HasOne',
]

components.forEach(registerComponent)

Vue.component('Panel', require('./Panel'))
Vue.component('Field', require('./Field'))
