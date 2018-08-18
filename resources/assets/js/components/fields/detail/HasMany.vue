<template>
    <div class="p-4">

        <div class="flex justify-between pb-4">
            <div>
                <h2 class="h2">{{ field.displayName }}</h2>
            </div>

        </div>

        <div class="panel">

            <Dial :source="source" :prefix="field.name" />

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
        }
    }
}
</script>
