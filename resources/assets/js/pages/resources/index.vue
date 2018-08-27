<template>
    <Panel title="Index">

        <template slot="buttons">
            <router-link :to="`/resources/${resource}/create`"
                class="btn btn-blue">
                Create
            </router-link>
        </template>

        <template slot="body">
            <Dial :source="`/api/resources/${resource}`" />
        </template>

    </Panel>
</template>

<script>
export default {
    computed: {
        resource() {
            return this.$route.params.resource
        }
    }
}
</script>
