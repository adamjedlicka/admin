<template>
    <ResourceDetail v-if="resource"
        :resource="resource"
        :model="model"
        :title="resource.title"
        action="detail" >

        <template slot="buttons">

            <router-link v-if="resource.policies.create"
                :to="`/resources/${resource.name}`"
                class="btn btn-blue" >
                Create
            </router-link>

        </template>

    </ResourceDetail>
</template>

<script>
export default {
    data() {
        return {
            resource: null,
            model: {},
        }
    },

    mounted() {
        this.fetchData()
    },

    methods: {
        async fetchData() {
            let resource = this.$route.params.resource
            let resourceKey = this.$route.params.resourceKey

            this.resource = await this.$get(`/api/resources/${resource}/${resourceKey}`)

            this.resource.fields.forEach(field => {
                this.model[field.name] = field.value
            })
        },

        async onDelete() {
            let ok = await modalConfirm('Delete', `Delete this record: ${this.resource.title} ?`, true)
            if (!ok) return

            let response = await this.$delete(`/api/resources/${this.resource.name}/${this.resource.key}`)
            if (response.status == 'success') {
                this.$router.push(`/resources/${this.resource.name}`)
            }
        }
    }
}
</script>
