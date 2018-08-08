<template>
    <div class="p-4" >
        <Dial v-if="resourceName" :source="`/api/resources/${resourceName}`" />
    </div>
</template>

<script>
import Url from '~/util/Url'

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
