<template>
    <Panel :name="field.displayName">

        <template slot="body">
            <Dial :source="source" :name="field.name" />
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

            return `/api/resources/${resource}/${resourceKey}/belongsToMany/${relationship}`
        },
    }
}
</script>
