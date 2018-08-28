<template>
    <router-link :to="url"
        class="link font-bold" >
        {{ field.meta.title }}
    </router-link>
</template>

<script>
export default {
    props: {
        field: Object,
        value: null,
    },

    computed: {
        url() {
            let resourceName = this.field.exports.relatedResourceName
            let resourceId = this.value

            return `/resources/${resourceName}/${resourceId}`
        }
    }
}
</script>
