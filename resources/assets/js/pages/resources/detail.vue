<template>
    <div v-if="resource">

        <Panel
            :displayName="resource.title"
            :fields="fields"
            action="detail" >

            <div slot="buttons">
                <router-link :to="editUrl" class="btn btn-blue">
                    Edit
                </router-link>
            </div>

        </Panel>

        <Panel v-for="(panel, i) in resource.panels" :key="i"
            :displayName="panel.displayName"
            :fields="panel.fields"
            action="detail" >

            <div slot="title">
                <h2 class="h2">{{ panel.displayName }}</h2>
            </div>

        </Panel>

        <div v-for="field in fieldPanels" :key="field.name"
            class="p-4" >
            <div slot="title">
                <h2 class="h2 pb-4">{{ field.displayName }}</h2>
            </div>

            <div class="panel">
                <component :is="`${field.type}-detail-field`"
                    :field="field" />
            </div>
        </div>

    </div>
</template>

<script>
export default {
    data() {
        return {
            resource: null
        }
    },

    computed: {
        indexUrl() {
            let resourceName = this.$route.params.resource

            return `/resources/${resourceName}`
        },

        editUrl() {
            let resourceName = this.$route.params.resource
            let id = this.$route.params.id

            return `/resources/${resourceName}/${id}/edit`
        },

        fields() {
            return this.resource.fields.filter(field => !field.isPanel)
        },

        fieldPanels() {
            return this.resource.fields.filter(field => field.isPanel)
        }
    },

    mounted() {
        this.fetchData()
    },

    methods: {
        async fetchData() {
            let resourceName = this.$route.params.resource
            let id = this.$route.params.id

            this.resource = await this.$get(`/api/resources/${resourceName}/${id}`)
        }
    }
}
</script>
