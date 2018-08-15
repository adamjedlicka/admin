<template>
    <router-link :to="urlDetail"
        class="link font-bold">
        {{ field.value }}
    </router-link>
</template>

<script>
export default {
    props: {
        field: Object,
    },

    computed: {
        urlDetail() {
            return `/resources/${this.field.name}/${this.field.value}`
        }
    }
}
</script>
