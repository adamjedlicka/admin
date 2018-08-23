<template>
    <tr
        class="hover:bg-grey-lighter" >

        <td v-for="(field, j) in fields" :key="j"
            class="p-4 text-black text-left" >

            <div class="truncate">
                <component
                    :is="`${field.type}-index-field`"
                    :field="field"
                    :value="row.data[field.name]"
                    :meta="row.meta[field.name]" />
            </div>

        </td>

        <!-- CRUD buttons -->
        <td class="text-right pr-2 whitespace-no-wrap">

            <router-link v-if="links.detail" :to="detailUrl"
                title="Detail"
                class="text-grey hover:text-black cursor-pointer" >
                <i class="py-4 px-1 far fa-eye"></i>
            </router-link>

            <router-link v-if="links.edit" :to="editUrl"
                title="Detail"
                class="text-grey hover:text-black cursor-pointer" >
                <i class="py-4 px-1 far fa-edit"></i>
            </router-link>

            <a v-if="links.edit" @click="onDelete"
                title="Detail"
                class="text-grey hover:text-red cursor-pointer" >
                <i class="py-4 px-1 far fa-trash-alt"></i>
            </a>

        </td>

    </tr>
</template>

<script>
import template from 'lodash/template'

export default {
    props: [
        'fields',
        'row',
        'links',
    ],

    computed: {
        detailUrl() {
            let compiled = template(this.links.detail)
            return compiled(this.row.data)
        },

        editUrl() {
            let compiled = template(this.links.edit)
            return compiled(this.row.data)
        },
    },

    methods: {
        async onDelete() {
            let ok = await modalConfirm('Delete', 'Delete this record?', true)
            if (!ok) return

            let compiled = template(this.links.delete)
            let deleteUrl = compiled(this.row)

            let response = await this.$delete(deleteUrl)
            if (response.status == 'success') {
                this.$emit('update')
            }
        }
    }
}
</script>
