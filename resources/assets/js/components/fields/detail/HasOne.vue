<template>
    <span>
        <router-link v-if="value"
            :to="detailUrl" class="link font-bold">
            {{ meta.title }}
        </router-link>
    </span>
</template>

<script>
export default {
    props: [
        'field',
        'meta',
        'value',
    ],

    computed: {
        detailUrl() {
            if (!this.value) return

            return `/resources/${this.field.meta.resource}/${this.value}`
        }
    }
}
</script>
