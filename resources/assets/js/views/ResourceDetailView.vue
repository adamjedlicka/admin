<template>
    <div v-if="resource" class="p-4">
        <div class="text-2xl font-bold pb-4">
            {{ resource.name }}
        </div>

        <div class="bg-white shadow-md rounded-lg py-2 px-4">
            <div v-for="(field, i) in fields" :key="i"
                class="py-4 flex" >

                <div class="text-lg text-grey-darker font-bold w-1/6">
                    {{ field.name }}
                </div>

                <div>
                    <conponent :is="`${field.type}-detail-field`" :value="resource.model[field.field]" />
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
            return this.resource.fields.filter(field => field.detailVisible)
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
