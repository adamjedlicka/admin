<template>
    <div class="p-4">

        <div class="flex justify-between pb-4">
            <div>
                <h2 class="h2">{{ field.displayName }}</h2>
            </div>

            <div>
                <router-link :to="createNew" class="btn btn-blue">
                    Create
                </router-link>
            </div>
        </div>

        <div class="panel">

            <Dial
                :source="source"
                :prefix="field.name"
                :query="query" />

        </div>

    </div>
</template>

<script>
export default {
    props: {
        field: Object,
    },

    computed: {
        source() {
            let resource = this.$route.params.resource
            let key = this.$route.params.key
            let ofWhat = this.field.name

            return `/api/resources/${resource}/${key}/hasMany/${ofWhat}`
        },

        createNew() {
            let resource = this.field.meta.relatedName.toLowerCase()
            let field = this.field.meta.relatedFieldName
            let key = this.$route.params.key
            let previous = this.$route.fullPath

            return `/resources/${resource}/create?via.${field}=${key}&previous=${previous}`
        },

        query() {
            return {
                previous: this.$route.fullPath,
                [`via.${this.field.meta.relatedFieldName}`]: this.$route.params.key,
            }
        }
    }
}
</script>
