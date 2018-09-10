<template>
    <tr
        class="hover:bg-grey-lighter" >

        <td v-for="(field, j) in row.fields" :key="j"
            class="p-4 text-black text-left" >

            <div class="truncate">
                <component
                    :is="`${field.type}-index-field`"
                    :field="field"
                    :value="field.value" />
            </div>

        </td>

        <td class="text-right pr-2 whitespace-no-wrap">
            <slot name="buttons" :resource="row" />
        </td>

    </tr>
</template>

<script>
export default {
    props: [
        'row',
        'links',
    ],

    computed: {
        detailUrl() {
            return `/resources/${this.row.name}/${this.row.key}`
        },

        editUrl() {
            return `/resources/${this.row.name}/${this.row.key}/edit`
        },

        deleteUrl() {
            return `/api/resources/${this.row.name}/${this.row.key}`
        }
    },
}
</script>
