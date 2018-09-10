<template>
    <span :title="zone">
        {{ display }}
    </span>
</template>

<script>
export default {
    props: [
        'field',
        'value',
    ],

    computed: {
        display() {
            return new Date(this.value).toLocaleString(document.documentElement.lang)
        },

        zone() {
            return new Date().toString().match(/\(([A-Za-z\s].*)\)/)[1]
        }
    }
}
</script>
