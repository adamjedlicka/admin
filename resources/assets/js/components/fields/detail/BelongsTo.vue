<template>
    <router-link :to="url"
        class="link font-bold" >
        {{ field.meta.value.title }}
    </router-link>
</template>

<script>
export default {
    props: {
        field: Object,
    },

    computed: {
        url() {
            let resourceName = this.field.meta.info.name
            let resourceId = this.field.value

            return `/resources/${resourceName}/${resourceId}`
        }
    }
}
</script>
