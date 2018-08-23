<template>
    <Panel :displayName="field.displayName">

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
        }
    }
}
</script>
