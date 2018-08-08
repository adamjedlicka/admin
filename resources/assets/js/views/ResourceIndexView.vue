<template>
    <div>
        <h1>{{ resource.displayName }}</h1>
    </div>
</template>

<script>
export default {
    data() {
        return {
            resource: null,
        }
    },

    async mounted() {
        let resourceName = this.$route.params.resource

        this.resource = await this.$get(`/api/resources/${resourceName}`)
    },
}
</script>
