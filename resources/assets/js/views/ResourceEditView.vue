<template>
    <div v-if="resource" class="p-4">
        <div class="flex justify-between pb-4">
            <div class="text-2xl font-bold">
                {{ resource.name }}
            </div>

            <div class="flex">
                <router-link :to="detailUrl" class="btn mr-2">
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
                        v-model="resource.model.attributes[field.field]"
                        :rules="field.rules" />
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
            resourceId: null,
            resource: null,
        }
    },

    computed: {
        fields() {
            return this.resource.fields.filter(field => field.editVisible)
        },

        detailUrl() {
            return `/resources/${this.resourceName}/${this.resourceId}`
        }
    },

    mounted() {
        this.resourceName = this.$route.params.resource
        this.resourceId = this.$route.params.id

        this.fetchData()
    },

    methods: {
        async fetchData() {
            this.resource = await this.$get(`/api/resources/${this.resourceName}/${this.resourceId}`)
        },

        async saveChanges() {
            let response = await this.$put(
                `/api/resources/${this.resourceName}/${this.resourceId}`,
                this.resource.model.attributes
            )

            if (response.status == 'success') {
                this.$router.push(`/resources/${this.resourceName}/${this.resourceId}`)
            }
        },
    }
}
</script>
