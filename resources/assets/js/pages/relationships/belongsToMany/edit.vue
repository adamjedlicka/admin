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
                    class="btn btn-blue"
                    @click="onUpdate" >
                    Update
                </a>

            </template>

            <template slot="body">

                <Field v-for="field in edit.fields" :key="field.name"
                    v-model="edit.data[field.name]"
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
            let relationship = this.$route.params.relationship
            let relationshipKey = this.$route.params.relationshipKey

            this.edit = await this.$get(`/api/relationships/${resource}/${resourceKey}/belongsToMany/${relationship}/${relationshipKey}/edit`)
        },

        async onUpdate() {
            let response = await this.$put(this.edit.links.update, this.edit.data)

            if (response.status == 'success') {
                let resourceName = this.$route.params.resource

                // this.$router.replace(`/resources/${resourceName}/${response.key}`)
                this.$router.go(-1)
            } else if (response.errors) {
                this.errors = response.errors
            }
        },
    }
}
</script>
