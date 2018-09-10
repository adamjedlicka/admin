<template>
    <Panel :title="field.displayName">

        <template slot="buttons">
            <router-link v-if="field.exports.policies.create"
                :to="createUrl"
                class="btn btn-blue" >
                New
            </router-link>
        </template>

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

            return `/api/resources/${resource}/${resourceKey}/hasMany/${relationship}`
        },

        createUrl() {
            let relatedResourceName = this.field.exports.relatedResourceName
            let relatedFieldName = this.field.exports.relatedFieldName
            let resourceKey = this.$route.params.resourceKey

            return `/resources/${relatedResourceName}/create?via.${relatedFieldName}=${resourceKey}`
        }
    }
}
</script>
