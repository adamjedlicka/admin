<template>
    <router-link :to="url"
        class="no-underline text-blue font-bold hover:text-blue-light" >
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
