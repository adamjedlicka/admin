<template>
    <Panel :displayName="field.displayName">

        <template slot="buttons">

            <router-link :to="attachUrl"
                class="btn btn-blue" >
                Attach
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

            return `/api/relationships/${resource}/${resourceKey}/belongsToMany/${relationship}`
        },

        attachUrl() {
            let resource = this.$route.params.resource
            let resourceKey = this.$route.params.resourceKey
            let relationship = this.field.name

            return `/resources/${resource}/${resourceKey}/attach/${relationship}`
        }
    }
}
</script>
