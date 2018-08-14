<template>
    <router-link :to="url"
        class="link font-bold" >
        {{ field.value.title }}
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
            let resourceId = this.field.value.key

            return `/resources/${resourceName}/${resourceId}`
        }
    }
}
</script>
