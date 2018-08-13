<template>
    <div v-if="resource" class="p-4">
        <div class="flex justify-between pb-4">
            <div class="text-2xl font-bold">
                Create new {{ resource.name }}
            </div>

            <div class="flex">
                <router-link :to="indexUrl" class="btn mr-2">
                    Cancel
                </router-link>

                <span @click.prevent="saveChanges" class="btn btn-green">
                    Save
                </span>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg py-4 px-8">
            <div v-for="(field, i) in fields" :key="i"
                class="py-6 flex"
                :class="{'border-t': i > 0}" >

                <div class="text-lg text-grey-dark font-bold w-1/6">
                    {{ field.name }}
                </div>

                <div class="text-lg text-grey-darkest w-5/6">
                    <conponent :is="`${field.type}-edit-field`"
                        v-model="form[field.field]"
                        :rules="field.rules" />

                    <div>
                        <div v-for="(error, j) in errors[field.field]" :key="j">
                            <span class="text-sm text-red p-1">{{ error }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            resourceName: null,
            resource: null,
            form: {},
            errors: [],
        }
    },

    computed: {
        fields() {
            return this.resource.fields.filter(field => field.visibleOn.includes('edit'))
        },

        indexUrl() {
            return `/resources/${this.resourceName}`
        }
    },

    mounted() {
        this.resourceName = this.$route.params.resource
        this.resourceId = this.$route.params.id

        this.fetchData()
    },

    methods: {
        async fetchData() {
            this.resource = await this.$get(`/api/resources/${this.resourceName}/create`)
            this.resource.fields.forEach(field => this.form[field.field] = null)
        },

        async saveChanges() {
            let response = await this.$post(
                `/api/resources/${this.resourceName}`,
                this.form
            )

            if (response.status == 'success') {
                this.$router.push(`/resources/${this.resourceName}/${response.id}`)
            } else if (response.errors) {
                this.errors = response.errors
            }
        },
    }
}
</script>
