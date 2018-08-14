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
                :action="action"
                :meta="meta(field.name)"
                @input="onInput" />

        </div>
    </div>
</template>

<script>
export default {
    props: {
        displayName: String,
        fields: Array,
        action: String,
    },

    methods: {
        meta(name) {
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
