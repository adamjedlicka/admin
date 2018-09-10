<template>
    <span>
        <router-link v-if="value"
            :to="detailUrl" class="link font-bold">
            {{ field.meta.title }}
        </router-link>
    </span>
</template>

<script>
export default {
    props: [
        'field',
        'value',
    ],

    computed: {
        detailUrl() {
            return `/resources/${this.field.exports.resource}/${this.value}`
        }
    }
}
</script>
