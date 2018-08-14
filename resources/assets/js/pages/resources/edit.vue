<template>
    <div v-if="resource">

        <Panel
            :displayName="resource.title"
            :fields="resource.fields"
            :errors="errors"
            action="edit"
            @input="onInput" >

            <div slot="buttons">
                <span class="btn btn-green" @click="saveChanges">
                    Save
                </span>
            </div>

        </Panel>

        <Panel v-for="(panel, i) in resource.panels" :key="i"
            :displayName="panel.displayName"
            :fields="panel.fields"
            :errors="errors"
            action="edit"
            @input="onInput" >

            <div slot="title">
                <h2 class="h2 pb-4">{{ panel.displayName }}</h2>
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

        resourceId() {
            return this.$route.params.id
        }
    },

    mounted() {
        this.fetchData()
    },

    methods: {
        async fetchData() {
            this.resource = await this.$get(`/api/resources/${this.resourceName}/${this.resourceId}/edit`)

            this.resource.fields
                .filter(field => field.visibleOn.includes('edit'))
                .forEach(field => this.model[field.name] = field.value)

            this.resource.panels
                .forEach(panel => {
                    panel.fields.forEach(field => this.model[field.name] = field.value)
                })

        },

        async saveChanges() {
            let response = await this.$put(
                `/api/resources/${this.resourceName}/${this.resourceId}`,
                this.model
            )

            if (response.status == 'success') {
                this.$router.push(`/resources/${this.resourceName}/${this.resourceId}`)
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
