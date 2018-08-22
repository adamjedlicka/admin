<template>
    <tr
        class="hover:bg-grey-lighter" >

        <td v-for="(field, j) in fields" :key="j"
            class="p-4 text-black text-left" >

            <div class="truncate">
                <component
                    :is="`${field.type}-index-field`"
                    :field="field"
                    :model="resource.model[field.name]" />
            </div>

        </td>

        <!-- CRUD buttons -->
        <td class="text-right pr-2 whitespace-no-wrap">
            <slot name="buttons" :resource="resource">
            </slot>
        </td>

    </tr>
</template>

<script>
export default {
    props: {
        resource: Object,
    },

    computed: {
        fields() {
            return this.resource.fields
        }
    },
}
</script>
