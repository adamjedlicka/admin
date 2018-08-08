<template>
    <h1>Hello, World!</h1>
</template>

<script>
export default {
    data() {
        return {
            resource: '',
        }
    },

    mounted() {
        this.resource = this.$route.params.resource
    },
}
</script>
