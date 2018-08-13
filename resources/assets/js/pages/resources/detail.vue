<template>
    <div v-if="value" class="p-4">
        <div class="flex justify-between pb-4">
            <div class="text-2xl font-bold">
                {{ value.name }}
            </div>

            <router-link :to="editUrl" class="btn btn-blue">
                Edit
            </router-link>
        </div>

        <div class="bg-white shadow-md rounded-lg py-4 px-8">
            <div v-for="(field, i) in value.fields" :key="i"
                class="py-6 flex"
                :class="{'border-t': i > 0}" >

                <div class="text-lg text-grey-dark font-bold w-1/6">
                    {{ field.displayName }}
                </div>

                <div class="text-lg text-grey-darkest w-5/6">
                    <conponent :is="`${field.type}-detail-field`"
                        :value="value.resource.attributes[field.name]"
                        :meta="metaOfField(field.name)" />
                </div>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            value: null,
        }
    },

    computed: {
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

            this.value = await this.$get(`/api/resources/${resourceName}/${id}`)
        },

        metaOfField(name) {
            for (let field of this.value.fields) {
                if (field.name == name) {
                    return field.meta
                }
            }
        }
    }
}
</script>
