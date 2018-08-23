<template>
    <div class="p-4" >
        <div class="flex justify-between pb-4">
            <h1 class="h1">
                Index
            </h1>

            <div>
                <router-link :to="`/resources/${resourceName}/create`"
                    class="btn btn-blue">
                    Create
                </router-link>
            </div>
        </div>

        <Dial :source="`/api/resources/${resourceName}`" />
    </div>
</template>

<script>
export default {
    computed: {
        resourceName() {
            return this.$route.params.resource
        }
    }
}
</script>
