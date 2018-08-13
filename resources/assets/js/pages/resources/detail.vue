<template>
    <div v-if="resource" class="p-4">
        <div class="flex justify-between pb-4">
            <div class="flex">
                <router-link :to="indexUrl" class="text-blue text-xl no-underline font-bold px-2">
                    <i class="fas fa-chevron-left align-text-bottom"></i>
                </router-link>

                <div class="text-2xl font-bold">
                    {{ resource.name }}
                </div>
            </div>

            <router-link :to="editUrl" class="btn btn-blue">
                Edit
            </router-link>
        </div>

        <div class="bg-white shadow-md rounded-lg py-4 px-8">
            <div v-for="(field, i) in fields" :key="i"
                class="py-6 flex"
                :class="{'border-t': i > 0}" >

                <div class="text-lg text-grey-dark font-bold w-1/6">
                    {{ field.name }}
                </div>

                <div class="text-lg text-grey-darkest w-5/6">
                    <conponent :is="`${field.type}-detail-field`" :value="resource.model.attributes[field.field]" />
                </div>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            resource: null,
        }
    },

    computed: {
        fields() {
            return this.resource.fields.filter(field => field.visibleOn.includes('detail'))
        },

        indexUrl() {
            let resourceName = this.$route.params.resource

            return `/resources/${resourceName}`
        },

        editUrl() {
            let resourceName = this.$route.params.resource
            let id = this.$route.params.id

            return `/resources/${resourceName}/${id}/edit`
        },
    },

    mounted() {
        this.fetchData()
    },

    methods: {
        async fetchData() {
            let resourceName = this.$route.params.resource
            let id = this.$route.params.id

            this.resource = await this.$get(`/api/resources/${resourceName}/${id}`)
        }
    }
}
</script>
