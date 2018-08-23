<template>
    <div v-if="detail">

        <Panel :displayName="detail.title" >

            <template slot="buttons">

                <a v-if="detail.links.delete"
                    class="btn btn-red"
                    @click="onDelete" >
                    Delete
                </a>

                <router-link v-if="detail.links.edit"
                    :to="detail.links.edit"
                    class="btn btn-blue" >
                    Edit
                </router-link>

            </template>

            <template slot="body">

                <Field v-for="field in fields" :key="field.name"
                    :field="field"
                    v-model="detail.data[field.name]"
                    :meta="detail.meta[field.name]"
                    action="detail" />

            </template>

        </Panel>

        <component v-for="panel in panels" :key="panel.name"
            :is="`${panel.type}-detail-field`"
            :v-model="detail.data[panel.name]"
            :field="panel" />

    </div>
</template>

<script>
export default {
    data() {
        return {
            detail: null,
        }
    },

    computed: {
        fields() {
            return this.detail.fields.filter(field => field.isPanel == false)
        },

        panels() {
            return this.detail.fields.filter(field => field.isPanel == true)
        },
    },

    mounted() {
        this.fetchData()
    },

    methods: {
        async fetchData() {
            let resourceName = this.$route.params.resource
            let key = this.$route.params.key

            this.detail = await this.$get(`/api/resources/${resourceName}/${key}`)
        },

        async onDelete() {
            let ok = await modalConfirm('Delete', 'Delete this record?', true)
            if (!ok) return

            let response = await this.$delete(this.detail.links.delete)
            if (response.status == 'success') {
                let resourceName = this.$route.params.resource

                this.$router.push(`/resources/${resourceName}`)
            }
        }
    }
}
</script>
