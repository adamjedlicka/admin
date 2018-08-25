<template>
    <Panel :displayName="field.displayName">

        <template slot="buttons">
            <router-link :to="createUrl" class="btn btn-blue">
                Create
            </router-link>
        </template>

        <template slot="body">
            <Dial :source="source" :prefix="field.name" />
        </template>

    </Panel>
</template>

<script>
export default {
    props: {
        field: Object,
    },

    computed: {
        source() {
            let resource = this.$route.params.resource
            let resourceKey = this.$route.params.resourceKey
            let relationship = this.field.name

            return `/api/relationships/${resource}/${resourceKey}/hasMany/${relationship}`
        },

        createUrl() {
            let resource = this.$route.params.resource
            let resourceKey = this.$route.params.resourceKey
            let relationship = this.field.name

            return `/relationships/${resource}/${resourceKey}/hasMany/${relationship}/create`
        }
    }
}
</script>
