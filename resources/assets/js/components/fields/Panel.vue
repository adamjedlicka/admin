<template>
    <div class="p-4">
        <slot name="title">
            <h1 class="h1 pb-4">{{ displayName }}</h1>
        </slot>

        <div class="panel">

            <Field v-for="(field, i) in fields" :key="i"
                :field="field"
                :action="action"
                :value="resource.attributes[field.name]"
                :meta="meta(field.name)" />

        </div>
    </div>
</template>

<script>
export default {
    props: {
        displayName: String,
        resource: Object,
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
        }
    }
}
</script>
