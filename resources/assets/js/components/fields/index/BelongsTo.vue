<template>
    <router-link :to="urlDetail"
        class="link font-bold">
        {{ field.meta.value.title }}
    </router-link>
</template>

<script>
export default {
    props: {
        field: Object,
    },

    computed: {
        urlDetail() {
            return `/resources/${this.field.meta.info.name}/${this.field.value}`
        }
    }
}
</script>
