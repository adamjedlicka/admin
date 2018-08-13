<template>
    <div v-if="value" class="p-4">
        <div class="flex justify-between pb-4">
            <div class="text-2xl font-bold">
                {{ value.displayName }}
            </div>

            <div class="flex">
                <router-link :to="`/resources/${this.resourceName}/${this.resourceId}`"
                    class="btn mr-2" >
                    Cancel
                </router-link>

                <span @click.prevent="saveChanges" class="btn btn-green">
                    Save
                </span>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg py-4 px-8">
            <div v-for="(field, i) in value.fields" :key="i"
                class="py-6 flex"
                :class="{'border-t': i > 0}" >

                <div class="text-lg text-grey-dark font-bold w-1/6">
                    {{ field.displayName }}
                </div>

                <div class="text-lg text-grey-darkest w-5/6">
                    <conponent :is="`${field.type}-edit-field`"
                        v-model="value.resource.attributes[field.name]" />

                    <div>
                        <div v-for="(error, j) in errors[field.name]" :key="j">
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
            resourceId: null,
            value: null,
            errors: [],
        }
    },

    mounted() {
        this.resourceName = this.$route.params.resource
        this.resourceId = this.$route.params.id

        this.fetchData()
    },

    methods: {
        async fetchData() {
            this.value = await this.$get(`/api/resources/${this.resourceName}/${this.resourceId}/edit`)
        },

        async saveChanges() {
            let response = await this.$put(
                `/api/resources/${this.resourceName}/${this.resourceId}`,
                this.value.resource.attributes
            )

            if (response.status == 'success') {
                this.$router.push(`/resources/${this.resourceName}/${this.resourceId}`)
            } else if (response.errors) {
                this.errors = response.errors
            }
        },
    }
}
</script>
