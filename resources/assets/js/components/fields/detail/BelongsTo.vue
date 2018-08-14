<template>
    <router-link :to="url"
        class="no-underline text-blue font-bold hover:text-blue-light" >
        {{ value.title }}
    </router-link>
</template>

<script>
export default {
    props: {
        field: Object,
    },

    computed: {
        url() {
            let resourceName = this.meta.name.toLowerCase()
            let resourceId = this.value.key

            return `/resources/${resourceName}/${resourceId}`
        }
    }
}
</script>
