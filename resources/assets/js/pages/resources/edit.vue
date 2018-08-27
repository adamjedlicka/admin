<template>
    <ResourceDetail v-if="resource"
        :resource="resource"
        :model="model"
        :errors="errors"
        title="Edit"
        action="edit" >

        <template slot="buttons">

            <a v-if="resource.policies.update"
                @click="onUpdate"
                class="btn btn-green" >
                Update
            </a>

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

            this.resource = await this.$get(`/api/resources/${resource}/${resourceKey}/edit`)

            this.resource.fields.forEach(field => {
                this.model[field.name] = field.value
            })
        },

        async onUpdate() {
            let response = await this.$put(`/api/resources/${this.resource.name}/${this.resource.key}`, this.model)

            if (response.status == 'success') {
                this.$router.push(`/resources/${this.resource.name}/${response.key}`)
            } else {
                this.errors = response.errors
            }
        },
    }
}
</script>
