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
            <router-link v-if="links.detail" :to="detailUrl"
                title="Detail"
                class="text-grey hover:text-black cursor-pointer" >
                <i class="py-4 px-1 far fa-eye"></i>
            </router-link>
        </td>

    </tr>
</template>

<script>
import template from 'lodash/template'

export default {
    props: {
        fields: Array,
        row: Object,
        links: Object,
    },

    computed: {
        detailUrl() {
            let compiled = template(this.links.detail)
            return compiled(this.row)
        }
    }
}
</script>
