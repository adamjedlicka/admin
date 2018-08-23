<template>
    <router-link :to="url"
        class="link font-bold" >
        {{ meta.title }}
    </router-link>
</template>

<script>
export default {
    props: {
        field: Object,
        value: null,
        meta: null,
    },

    computed: {
        url() {
            let resourceName = this.field.meta.name
            let resourceId = this.value

            return `/resources/${resourceName}/${resourceId}`
        }
    }
}
</script>
