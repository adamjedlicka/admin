<template>
    <div v-if="resourceName" class="p-4" >
        <div class="flex justify-between pb-4">
            <div class="text-2xl font-bold">
                Index
            </div>

            <router-link :to="createUrl" class="btn btn-blue">
                Create
            </router-link>
        </div>

        <Dial ref="dial" :source="`/api/resources/${resourceName}`" />
    </div>
</template>

<script>
import Url from '~/support/Url'

export default {
    data() {
        return {
            resourceName: null,
        }
    },

    computed: {
        createUrl() {
            return `/resources/${this.resourceName}/create`
        }
    },

    async mounted() {
        this.resourceName = this.$route.params.resource
    },
}
</script>
