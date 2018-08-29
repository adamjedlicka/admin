<template>
    <ResourceDetail v-if="resource"
        :resource="resource"
        :value="model"
        :errors="errors"
        title="Create"
        action="edit"
        @input="onInput" >

        <template slot="buttons">

            <a @click="onCancel"
                class="btn btn-blue" >
                Cancel
            </a>

            <a v-if="resource.policies.create"
                @click="onCreate"
                class="btn btn-green" >
                Store
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

            this.resource = await this.$get(`/api/resources/${resource}/create`)

            this.resource.fields.forEach(field => {
                this.model[field.name] = field.value
            })
        },

        async onCreate() {
            let response = await this.$post(`/api/resources/${this.resource.name}`, this.model)

            if (response.status == 'success') {
                this.$router.replace(`/resources/${this.resource.name}/${response.key}`)
            } else {
                this.errors = response.errors
            }
        },

        onCancel() {
            this.$router.go(-1)
        },

        onInput(field, value) {
            this.$set(this.model, field, value)
            this.$forceUpdate()
        }
    }
}
</script>
