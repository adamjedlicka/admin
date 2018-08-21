<template>
    <div class="p-4">

        <div class="flex justify-between pb-4">
            <div>
                <h2 class="h2">{{ field.displayName }}</h2>
            </div>

        </div>

        <div class="panel">

            <Field v-for="(field, i) in field.meta.fields" :key="i"
                :field="field"
                :errors="errorsOf(field.name)"
                action="edit"
                @input="onInput" />

        </div>

    </div>
</template>

<script>
export default {
    props: {
        field: Object,
        model: Object,
        errors: Object,
    },

    methods: {
        onInput(name, value) {
            let model = this.model || {}
            model[name] = value

            this.$emit('input', this.field.name, model)
        },

        errorsOf(name) {
            return this.errors[`${this.field.name}.${name}`]
        }
    }
}
</script>
