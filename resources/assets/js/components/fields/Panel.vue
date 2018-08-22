<template>
    <div class="p-4">
        <div class="flex justify-between pb-4">
            <slot name="title">
                <h1 class="h1">{{ displayName }}</h1>
            </slot>

            <slot name="buttons">
            </slot>
        </div>

        <div class="panel">
            <slot name="body">

                <Field v-for="(field, i) in fields" :key="i"
                    :field="field"
                    :model="model[field.name]"
                    :errors="errorsOf(field.name)"
                    :action="action"
                    :meta="metaOf(field.name)"
                    @input="onInput" />

            </slot>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        displayName: null,
        fields: Array,
        model: null,
        errors: Object,
        action: String,
    },

    methods: {
        errorsOf(name) {
            return this.errors ? this.errors[name] : []
        },

        metaOf(name) {
            for (let field of this.fields) {
                if (field.name == name) {
                    return field.meta
                }
            }
        },

        onInput(...args) {
            this.$emit('input', ...args)
        }
    }
}
</script>
