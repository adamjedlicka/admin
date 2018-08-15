<template>
    <div>

        <Field v-for="(field, i) in field.meta.fields" :key="i"
            :field="field"
            :errors="errorsOf(field.name)"
            action="edit"
            :meta="metaOf(field.name)"
            @input="onInput" />

    </div>
</template>

<script>
export default {
    props: {
        field: Object,
        errors: Object,
        model: Object,
    },

    data() {
        return {
            value: this.model[this.field.name] || {},
        }
    },

    methods: {
        errorsOf(name) {
            return this.errors ? this.errors[name] : []
        },

        metaOf(name) {
            for (let field of this.field.meta.fields) {
                if (field.name == name) {
                    return field.meta
                }
            }
        },

        onInput(name, value) {
            this.value[name] = value
            this.$emit('input', this.field.name, this.value)
        }
    }
}
</script>
