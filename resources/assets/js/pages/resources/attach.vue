<template>
    <ResourceDetail v-if="resource"
        :resource="resource"
        :value="model"
        :errors="errors"
        title="Attach"
        action="edit"
        @input="onInput" >

        <template slot="buttons">
            <a v-if="resource.policies.attach"
                @click="onAttach"
                class="btn btn-blue" >
                Attach
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

    computed: {
        source() {
            let resource = this.$route.params.resource
            let resourceKey = this.$route.params.resourceKey
            let relationship = this.$route.params.relationship

            return `/api/resources/${resource}/${resourceKey}/belongsToMany/${relationship}/attach`;
        }

    },

    mounted() {
        this.fetchData()
    },

    methods: {
        async fetchData() {
            this.resource = await this.$get(this.source)

            this.resource.fields.forEach(field => {
                this.model[field.name] = field.value ? field.value : field.default
            })
        },

        async onAttach() {
            let response = await this.$post(this.source, this.model)

            if (response.status == 'success') {
                this.$router.go(-1)
            }
        },

        onInput(field, value) {
            this.$set(this.model, field, value)
            this.$forceUpdate()
        }
    }
}
</script>
