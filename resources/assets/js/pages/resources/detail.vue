<template>
    <div v-if="resource">

        <Panel :displayName="resource.title" >

            <template slot="buttons">

                <a v-if="resource.policies.delete"
                    @click="onDelete"
                    class="btn btn-red" >
                    Delete
                </a>

                <router-link v-if="resource.policies.update"
                    :to="`/resources/${resource.name}/${resource.key}/edit`"
                    class="btn btn-blue" >
                    Edit
                </router-link>

            </template>

            <template slot="body">

                <Field v-for="field in fields" :key="field.name"
                    :field="field"
                    v-model="field.value"
                    action="detail" />

            </template>

        </Panel>

        <component v-for="panel in panels" :key="panel.name"
            :is="`${panel.type}-detail-field`"
            :field="panel"
            :v-model="panel.value" />

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
            return this.resource.fields.filter(field => field.isPanel == false)
        },

        panels() {
            return this.resource.fields.filter(field => field.isPanel == true)
        },
    },

    mounted() {
        this.fetchData()
    },

    methods: {
        async fetchData() {
            let resource = this.$route.params.resource
            let resourceKey = this.$route.params.resourceKey

            this.resource = await this.$get(`/api/resources/${resource}/${resourceKey}`)
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
