<template>
    <ResourceDetail v-if="resource"
        :resource="resource"
        :value="model"
        :errors="errors"
        title="Attach"
        action="edit"
        @input="onInput" >

        <template slot="buttons">

        </template>

    </ResourceDetail>
</template>

<script>
export default {
    data() {
        return {
            resource: null,
            model: {},
            errors: {},
        }
    },

    mounted() {
        this.fetchData()
    },

    methods: {
        async fetchData() {
            let resource = this.$route.params.resource
            let resourceKey = this.$route.params.resourceKey
            let relationship = this.$route.params.relationship

            this.resource = await this.$get(`/api/resources/${resource}/${resourceKey}/belongsToMany/${relationship}/attach`)

            this.resource.fields.forEach(field => {
                this.model[field.name] = field.value
            })
        },

        onInput(field, value) {
            this.$set(this.model, field, value)
            this.$forceUpdate()
        }
    }
}
</script>
