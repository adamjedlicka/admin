<template>
    <ResourceDetail v-if="resource"
        :resource="resource"
        :value="model"
        :errors="errors"
        title="Attach"
        action="edit"
        @input="onInput" >

        <template slot="buttons">
            <a @click="onCancel"
                class="btn btn-blue" >
                Cancel
            </a>

            <a v-if="resource.policies.attach"
                @click="onAttach"
                class="btn btn-green" >
                Attach
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
            let resourceKey = this.$route.params.resourceKey
            let relationship = this.$route.params.relationship

            return `/api/resources/${resource}/${resourceKey}/belongsToMany/${relationship}/attach`;
        }
    },

    methods: {
        async onAttach() {
            let response = await this.$post(this.source, this.model)

            if (response.status == 'success') {
                this.$router.go(-1)
            } else {
                this.errors = response.errors
            }
        },

        onCancel() {
            this.$router.go(-1)
        }
    }
}
</script>
