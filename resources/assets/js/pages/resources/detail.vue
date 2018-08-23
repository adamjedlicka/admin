<template>
    <div v-if="detail">

        <Panel :displayName="detail.title" >

            <template slot="buttons">

                <router-link v-if="detail.links.edit"
                    :to="detail.links.edit"
                    class="btn btn-blue" >
                    Edit
                </router-link>

            </template>

            <template slot="body">

                <Field v-for="field in detail.fields" :key="field.name"
                    v-model="detail.data[field.name]"
                    :field="field"
                    action="detail" />

            </template>

        </Panel>

    </div>
</template>

<script>
export default {
    data() {
        return {
            detail: null,
        }
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

            let resourceName = this.$route.params.resource
            let key = this.$route.params.key

            let response = await this.$delete(`/api/resources/${resourceName}/${key}`)
            if (response.status == 'success') {
                this.$router.push(`/resources/${resourceName}`)
            }
        }
    }
}
</script>
