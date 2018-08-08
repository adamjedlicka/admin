<template>
    <div
        class="bg-white shadow-md rounded-lg min-w-100 overflow-auto" >

        <DialHeader :resource="resource" :fields="fields" />

        <DialBody :resource="resource" :fields="fields" />

        <DialPagination :current="resource.data ? resource.data.current_page : 0" />

    </div>
</template>

<script>
import DialPagination from './DialPagination'
import DialHeader from './DialHeader'
import DialBody from './DialBody'

export default {
    props: {
        resource: Object,
    },

    computed: {
        fields() {
            return this.resource.fields
                ? this.resource.fields.filter(field => field.indexVisible)
                : []
        }
    },

    methods: {
        fieldWidth(field) {
            switch (field.indexSize) {
                case 'small':
                    return 'w-16'
                    break
                default:
                    return 'flex-1'
                    break
            }
        }
    },

    components: {
        DialPagination,
        DialHeader,
        DialBody,
    }
}
</script>
