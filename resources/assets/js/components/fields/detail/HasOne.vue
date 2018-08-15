<template>
    <div>

        <Field v-for="(field, i) in field.meta.fields" :key="i"
            :field="field"
            action="detail"
            :meta="metaOf(field.name)" />

    </div>
</template>

<script>
export default {
    props: {
        field: Object,
    },

    data() {
        return {
            model: {},
        }
    },

    methods: {
        metaOf(name) {
            for (let field of this.field.meta.fields) {
                if (field.name == name) {
                    return field.meta
                }
            }
        }
    }
}
</script>
