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
            let key = this.$route.params.key
            let relationship = this.field.name

            return `/api/relationships/${resource}/${key}/hasMany/${relationship}`
        },

        createUrl() {
            let resource = this.$route.params.resource
            let key = this.$route.params.key
            let relationship = this.field.name

            return `/relationships/${resource}/${key}/hasMany/${relationship}/create`
        }
    }
}
</script>
