<template>
    <div>

        <Panel :title="title" >

            <template slot="buttons">
                <slot name="buttons" />
            </template>

            <template slot="body">

                <Field v-for="field in fields" :key="field.name"
                    :field="field"
                    :value="value[field.name]"
                    :errors="errors ? errors[field.name] : []"
                    :action="action"
                    @input="$emit('input', field.name, $event)" />

            </template>

        </Panel>

        <component v-for="panel in panels" :key="panel.name"
            :is="`${panel.type}-${action}-field`"
            :field="panel"
            :value="value[panel.name]"
            :errors="errors ? errors[field.name] : []" />

    </div>
</template>

<script>
export default {
    props: [
        'resource',
        'value',
        'errors',
        'title',
        'action',
    ],

    computed: {
        fields() {
            return this.resource.fields.filter(field => field.isPanel == false)
        },

        panels() {
            return this.resource.fields.filter(field => field.isPanel == true)
        },
    },
}
</script>
