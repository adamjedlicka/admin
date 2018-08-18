<template>
    <div v-if="resource">

        <Panel
            :displayName="resource.title"
            :fields="fields"
            :model="model"
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

        <component v-for="field in panels" :key="field.name"
            :is="`${field.type}-edit-field`"
            :field="field"
            :model="model[field.name]"
            :errors="errors"
            @input="onInput" />

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

        resourceKey() {
            return this.$route.params.key
        },

        fields() {
            return this.resource.fields.filter(field => !field.isPanel)
        },

        panels() {
            return this.resource.fields.filter(field => field.isPanel)
        },

        detailUrl() {
            return `/resources/${this.resourceName}/${this.resourceKey}`
        }
    },

    mounted() {
        this.fetchData()
    },

    methods: {
        async fetchData() {
            this.resource = await this.$get(`/api/resources/${this.resourceName}/${this.resourceKey}/edit`)

            this.resource.fields.forEach(field => this.model[field.name] = field.value)
        },

        async saveChanges() {
            let response = await this.$put(
                `/api/resources/${this.resourceName}/${this.resourceKey}`,
                this.model
            )

            if (response.status == 'success') {
                this.$router.push(`/resources/${this.resourceName}/${this.resourceKey}`)
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
