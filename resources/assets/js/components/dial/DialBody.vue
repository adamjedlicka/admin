<template>
    <tbody>

        <DialBodyRow v-for="(row, i) in rows" :key="i"
            class="hover:bg-grey-lighter"
            :fields="fields"
            :row="row"
            @update="$emit('update')" >

            <template v-if="!!$scopedSlots.buttons" slot="buttons" slot-scope="scope">
                <slot name="buttons" :row="scope.row">
                    <router-link :to="detailUrl(row)"
                        title="Detail"
                        class="text-grey hover:text-black cursor-pointer" >
                        <i class="py-4 px-1 far fa-eye"></i>
                    </router-link>

                    <router-link :to="editUrl(row)"
                        title="Edit"
                        class="text-grey hover:text-black cursor-pointer" >
                        <i class="py-4 px-1 far fa-edit"></i>
                    </router-link>

                    <span class="text-grey hover:text-red cursor-pointer"
                        title="Delete"
                        @click="onDelete(row)" >
                        <i class="py-4 px-1 far fa-trash-alt"></i>
                    </span>
                </slot>
            </template>

        </DialBodyRow>

    </tbody>
</template>

<script>
import DialBodyRow from './DialBodyRow'

export default {
    props: {
        fields: Array,
        rows: Array,
    },

    methods: {
        detailUrl(row) {
            let resourceName = row.name
            let id = row.key

            return `/resources/${resourceName}/${id}`
        },

        editUrl(row) {
            return this.detailUrl(row) + '/edit'
        },

        async onDelete(row) {
            let resourceName = row.name
            let id = row.key

            let ok = await modalConfirm('Delete', 'Delete this record?', true)
            if (ok) {
                let response = await this.$delete(`/api/resources/${resourceName}/${id}`)
                if (response.status == 'success') {
                    this.$emit('update')
                }
            }
        },
    },

    components: {
        DialBodyRow,
    }
}
</script>
