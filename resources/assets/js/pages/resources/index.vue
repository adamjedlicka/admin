<template>
    <div v-if="resourceName" class="p-4" >
        <div class="flex justify-between pb-4">
            <h1 class="h1">
                Index
            </h1>

            <div>
                <router-link :to="`/resources/${this.resourceName}/create`"
                    class="btn btn-blue">
                    Create
                </router-link>
            </div>
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

    async mounted() {
        this.resourceName = this.$route.params.resource
    },
}
</script>
