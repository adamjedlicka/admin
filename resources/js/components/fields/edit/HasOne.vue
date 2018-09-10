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
            let resource = this.$route.params.resource
            let resourceKey = this.$route.params.resourceKey

            return `/relationships/${resource}/${resourceKey}/hasOne/${this.field.name}/create`
        },
    }
}
</script>
