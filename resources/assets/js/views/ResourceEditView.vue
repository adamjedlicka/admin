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

                <router-link :to="detailUrl" class="btn btn-green">
                    Save
                </router-link>
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
                    <conponent :is="`${field.type}-edit-field`" :value="resource.model.fields[field.field]" />
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
            return this.resource.fields.filter(field => field.editVisible)
        },

        detailUrl() {
            let resourceName = this.$route.params.resource
            let id = this.$route.params.id

            return `/resources/${resourceName}/${id}`
        }
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
