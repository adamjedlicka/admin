<template>
    <div v-if="resource">
        {{ resource.name }}

        <div v-for="(field, i) in fields" :key="i">
            <conponent :is="`${field.type}-detail-field`" :value="resource.model[field.field]" />
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            resource: null,
        }
    },

    computed: {
        fields() {
            return this.resource.fields.filter(field => field.detailVisible)
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
