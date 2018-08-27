<template>
    <Panel v-if="attach" :displayName="attach.title">

        <template slot="buttons">
            <a class="btn btn-green" @click="onAttach">Attach</a>
        </template>

        <template slot="body">

            <Field v-for="field in attach.fields" :key="field.name"
                    v-model="attach.data[field.name]"
                    :field="field"
                    :errors="errors[field.name]"
                    action="edit" />

        </template>

    </Panel>
</template>

<script>
export default {
    data() {
        return {
            attach: null,
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

            this.attach = await this.$get(`/api/relationships/${resource}/${resourceKey}/belongsToMany/${relationship}/attach`)
        },

        async onAttach() {
            let response = await this.$post(this.attach.links.attach, this.attach.data)

            if (response.status == 'success') {
                this.$router.go(-1)
            } else if (response.errors) {
                this.errors = response.errors
            }
        }
    }
}
</script>
