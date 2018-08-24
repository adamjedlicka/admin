<template>
    <div v-if="edit">

        <Panel :displayName="edit.title" >

            <template slot="buttons">

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
            let resourceName = this.$route.params.resource
            let key = this.$route.params.key

            this.edit = await this.$get(`/api/resources/${resourceName}/${key}/edit`)
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
