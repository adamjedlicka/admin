<template>
    <div v-if="resource">

        <Panel
            :displayName="resource.title"
            :fields="fields"
            :errors="errors"
            action="edit"
            @input="onInput" >

            <div slot="buttons" class="buttons">
                <router-link :to="detailUrl" class="btn">
                    Cancel
                </router-link>

                <span class="btn btn-green" @click="saveChanges">
                    Save
                </span>
            </div>

        </Panel>

    </div>
</template>

<script>
export default {
    data() {
        return {
            resource: null,
            model: {},
            errors: {},
        }
    },

    computed: {
        resourceName() {
            return this.$route.params.resource
        },

        resourceKy() {
            return this.$route.params.id
        },

        fields() {
            return this.resource.fields
        },

        detailUrl() {
            return `/resources/${this.resourceName}/${this.resourceKy}`
        }
    },

    mounted() {
        this.fetchData()
    },

    methods: {
        async fetchData() {
            this.resource = await this.$get(`/api/resources/${this.resourceName}/${this.resourceKy}/edit`)

            this.fields.forEach(field => this.model[field.name] = field.value)

        },

        async saveChanges() {
            let response = await this.$put(
                `/api/resources/${this.resourceName}/${this.resourceKy}`,
                this.model
            )

            if (response.status == 'success') {
                this.$router.push(`/resources/${this.resourceName}/${this.resourceKy}`)
            } else if (response.errors) {
                this.errors = response.errors
            }
        },

        onInput(name, value) {
            this.model[name] = value
        }
    }
}
</script>
