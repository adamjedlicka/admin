<template>
    <ResourceDetail v-if="resource"
        :resource="resource"
        :value="model"
        :errors="errors"
        title="Edit"
        action="edit"
        @input="onInput" >

        <template slot="buttons">

            <a @click="onCancel"
                class="btn btn-blue" >
                Cancel
            </a>

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
                this.$router.go(-1)
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
