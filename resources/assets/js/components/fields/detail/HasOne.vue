<template>
    <span>
        <router-link v-if="value"
            :to="detailUrl" class="link font-bold">
            {{ field.meta.title }}
        </router-link>
        <router-link v-else
            :to="createUrl" class="link font-bold">
            Create
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
            if (!this.value) return

            return `/resources/${this.field.exports.resource}/${this.value}`
        },

        createUrl() {
            let key = this.$route.params.resourceKey
            let resource = this.field.exports.resource
            let relatedFieldName = this.field.exports.relatedFieldName

            return `/resources/${resource}/create?via.${relatedFieldName}=${key}`
        },
    }
}
</script>
