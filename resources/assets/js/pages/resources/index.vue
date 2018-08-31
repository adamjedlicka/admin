<template>
    <div class="p-4">
        <div class="flex justify-between pb-4">
            <h1 class="h1">Index</h1>

            <div class="buttons">
                <router-link :to="`/resources/${resource}/create`"
                    class="btn btn-blue">
                    Create
                </router-link>
            </div>
        </div>

        <Dial :source="`/api/resources/${resource}`" />
    </div>
</template>

<script>
export default {
    computed: {
        resource() {
            return this.$route.params.resource
        }
    }
}
</script>
