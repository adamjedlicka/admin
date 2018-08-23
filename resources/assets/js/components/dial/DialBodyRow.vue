<template>
    <tr
        class="hover:bg-grey-lighter" >

        <td v-for="(field, j) in fields" :key="j"
            class="p-4 text-black text-left" >

            <div class="truncate">
                <component
                    :is="`${field.type}-index-field`"
                    :field="field"
                    :model="row[field.name]" />
            </div>

        </td>

        <!-- CRUD buttons -->
        <td class="text-right pr-2 whitespace-no-wrap">
            <slot name="buttons" :row="row">
            </slot>
        </td>

    </tr>
</template>

<script>
export default {
    props: {
        fields: Array,
        row: Object,
    },
}
</script>
