<template>
    <div v-if="resource">

        <Panel
            :displayName="resource.title"
            :fields="fields"
            :model="model"
            action="detail" >

            <div slot="buttons">
                <router-link :to="editUrl" class="btn btn-blue">
                    Edit
                </router-link>
            </div>

        </Panel>

        <component v-for="field in panels" :key="field.name"
            :is="`${field.type}-detail-field`"
            :field="field" />

    </div>
</template>

<script>
export default {
    data() {
        return {
            resource: null,
            model: {},
        }
    },

    computed: {
        indexUrl() {
            let resourceName = this.$route.params.resource

            return `/resources/${resourceName}`
        },

        editUrl() {
            let resourceName = this.$route.params.resource
            let key = this.$route.params.key

            return `/resources/${resourceName}/${key}/edit`
        },

        fields() {
            return this.resource.fields.filter(field => !field.isPanel)
        },

        panels() {
            return this.resource.fields.filter(field => field.isPanel)
        },
    },

    mounted() {
        this.fetchData()
    },

    methods: {
        async fetchData() {
            let resourceName = this.$route.params.resource
            let key = this.$route.params.key

            this.resource = await this.$get(`/api/resources/${resourceName}/${key}`)

            this.resource.fields.forEach(field => this.model[field.name] = field.value || null)
        }
    }
}
</script>
