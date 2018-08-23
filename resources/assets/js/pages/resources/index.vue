<template>
    <Panel displayName="Index">

        <template slot="buttons">
            <router-link :to="`/resources/${resourceName}/create`"
                class="btn btn-blue">
                Create
            </router-link>
        </template>

        <template slot="body">
            <Dial :source="`/api/resources/${resourceName}`" />
        </template>

    </Panel>
</template>

<script>
export default {
    computed: {
        resourceName() {
            return this.$route.params.resource
        }
    }
}
</script>
