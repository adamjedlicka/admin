<template>
    <div v-if="create">

        <Panel :displayName="create.title" >

            <template slot="buttons">

                <a v-if="create.links.store"
                    class="btn btn-blue"
                    @click="store" >
                    Store
                </a>

            </template>

            <template slot="body">

                <Field v-for="field in create.fields" :key="field.name"
                    v-model="create.data[field.name]"
                    :field="field"
                    :errors="errors[field.name]"
                    action="edit" />

            </template>

        </Panel>

    </div>
</template>

<script>
export default {
    data() {
        return {
            create: null,
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

            this.create = await this.$get(`/api/relationships/${resource}/${resourceKey}/hasMany/${relationship}/create`)
        },

        async store() {
            let response = await this.$post(this.create.links.store, this.create.data)

            if (response.status == 'success') {
                let resource = this.$route.params.resource
                let resourceKey = this.$route.params.resourceKey

                this.$router.push(`/resources/${resource}/${resourceKey}`)
            } else if (response.errors) {
                this.errors = response.errors
            }
        }
    }
}
</script>
