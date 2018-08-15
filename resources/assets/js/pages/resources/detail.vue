<template>
    <div v-if="resource">

        <Panel
            :displayName="resource.title"
            :fields="resource.fields"
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
