<template>
    <div
        class="bg-white shadow-md rounded-lg min-w-100 overflow-auto" >

        <DialHeader :resource="resource" :fields="fields" />

        <DialBody :resource="resource" :fields="fields" />

    </div>
</template>

<script>
import DialHeader from './DialHeader'
import DialBody from './DialBody'

export default {
    props: {
        resource: Object,
    },

    computed: {
        fields() {
            return this.resource.fields.filter(field => field.visibleIndex)
        }
    },

    components: {
        DialHeader,
        DialBody,
    }
}
</script>
