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
import HasResource from './HasResource'

export default {
    mixins: [
        HasResource,
    ],

    computed: {
        source() {
            let resource = this.$route.params.resource

            return `/api/resources/${resource}/create`
        }
    },

    methods: {
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

        valueOf(field) {
            return field.value || field.default
        }
    }
}
</script>
