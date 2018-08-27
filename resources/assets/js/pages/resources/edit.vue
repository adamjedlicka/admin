<template>
    <div v-if="edit">

        <Panel :displayName="edit.title" >

            <template slot="buttons">

                <a v-if="edit.links.update"
                    class="btn"
                    @click="$router.go(-1)" >
                    Cancel
                </a>

                <a v-if="edit.links.update"
                    class="btn btn-green"
                    @click="onUpdate" >
                    Update
                </a>

            </template>

            <template slot="body">

                <Field v-for="field in edit.fields" :key="field.name"
                    :field="field"
                    v-model="edit.data[field.name]"
                    :meta="edit.meta[field.name]"
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
            edit: null,
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

            this.edit = await this.$get(`/api/resources/${resource}/${resourceKey}/edit`)
        },

        async onUpdate() {
            let response = await this.$put(this.edit.links.update, this.edit.data)

            if (response.status == 'success') {
                let resource = this.$route.params.resource

                // this.$router.replace(`/resources/${resource}/${response.key}`)
                this.$router.go(-1)
            } else if (response.errors) {
                this.errors = response.errors
            }
        },
    }
}
</script>
