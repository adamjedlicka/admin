<template>
    <span>{{ display }}</span>
</template>

<script>
export default {
    props: {
        value: null,
    },

    computed: {
        display() {
            return new Date(this.value.value.date).toLocaleDateString(locale())
        }
    }
}
</script>
