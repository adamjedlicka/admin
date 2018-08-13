<template>
    <div v-if="resourceName" class="p-4" >
        <div class="text-2xl font-bold pb-4">
            Index
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
