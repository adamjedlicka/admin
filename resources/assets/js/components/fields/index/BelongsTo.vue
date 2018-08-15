<template>
    <router-link :to="urlDetail"
        class="link font-bold">
        {{ field.meta.title }}
    </router-link>
</template>

<script>
export default {
    props: {
        field: Object,
    },

    computed: {
        urlDetail() {
            return `/resources/${this.field.meta.name}/${this.field.value}`
        }
    }
}
</script>
