<template>
    <span>
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
            if (!this.value) return null

            return new Date(this.value).toLocaleDateString(document.documentElement.lang)
        }
    }
}
</script>
