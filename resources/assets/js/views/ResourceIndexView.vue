<template>
    <div
        class="p-4" >
        <h1
            class="pb-4" >
            {{ resource.displayName }}
        </h1>

        <Dial :resource="resource" />
    </div>
</template>

<script>
export default {
    data() {
        return {
            resource: {},
        }
    },

    async mounted() {
        let resourceName = this.$route.params.resource

        this.resource = await this.$get(`/api/resources/${resourceName}`)
    },
}
</script>
