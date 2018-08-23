<template>
    <router-link :to="urlDetail"
        class="link font-bold">
        {{ field.value.title }}
    </router-link>
</template>

<script>
export default {
    props: {
        field: Object,
        value: null,
    },

    computed: {
        urlDetail() {
            return `/resources/${this.field.meta.name}/${this.value}`
        }
    }
}
</script>
