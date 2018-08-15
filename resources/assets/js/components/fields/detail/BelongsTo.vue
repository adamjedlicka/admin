<template>
    <router-link :to="url"
        class="link font-bold" >
        {{ field.value }}
    </router-link>
</template>

<script>
export default {
    props: {
        field: Object,
    },

    computed: {
        url() {
            let resourceName = this.field.name
            let resourceId = this.field.value

            return `/resources/${resourceName}/${resourceId}`
        }
    }
}
</script>
