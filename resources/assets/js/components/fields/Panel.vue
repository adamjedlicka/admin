<template>
    <div class="p-4">
        <div class="flex justify-between">
            <slot name="title">
                <h1 class="h1 pb-4">{{ displayName }}</h1>
            </slot>

            <slot name="buttons">
            </slot>
        </div>

        <div class="panel">

            <Field v-for="(field, i) in fields" :key="i"
                :field="field"
                :errors="errorsOf(field.name)"
                :action="action"
                :meta="metaOf(field.name)"
                @input="onInput" />

        </div>
    </div>
</template>

<script>
export default {
    props: {
        displayName: null,
        fields: Array,
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
