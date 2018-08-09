<template>
    <input :value="display">
</template>

<script>
export default {
    props: {
        value: null,
    },

    computed: {
        display() {
            return new Date(this.value.date).toLocaleString(locale())
        }
    }
}
</script>
