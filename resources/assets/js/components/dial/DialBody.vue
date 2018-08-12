<template>
    <tbody>

        <tr v-for="(row, i) in rows" :key="i"
            class="hover:bg-grey-lighter" >

            <td v-for="(field, j) in fields" :key="j"
                class="p-4 text-black text-left" >

                <component :is="`${field.type}-index-field`" :value="row.attributes[field.field]" />

            </td>

            <!-- CRUD buttons -->
            <td class="text-right truncate pr-2">
                <router-link :to="detailUrl(row)"
                    class="text-grey hover:text-black cursor-pointer" >
                    <i class="py-4 px-1 far fa-eye"></i>
                </router-link>

                <router-link :to="editUrl(row)"
                    class="text-grey hover:text-black cursor-pointer" >
                    <i class="py-4 px-1 far fa-edit"></i>
                </router-link>

                <span class="text-grey hover:text-red cursor-pointer"
                    @click="onDelete(row)" >
                    <i class="py-4 px-1 far fa-trash-alt"></i>
                </span>
            </td>

        </tr>

    </tbody>
</template>

<script>
export default {
    props: {
        resource: Object,
        fields: Array,
    },

    computed: {
        rows() {
            return this.resource.data.rows
        }
    },

    methods: {
        detailUrl(row) {
            let resourceName = this.$route.params.resource
            let id = row.attributes.id

            return `/resources/${resourceName}/${id}`
        },

        editUrl(row) {
            return this.detailUrl(row) + '/edit'
        },

        async onDelete(row) {
            let resourceName = this.$route.params.resource
            let id = row.attributes.id

            let ok = await modalConfirm('Delete', 'Delete this record?', true)
            if (ok) {
                let response = await this.$delete(`/api/resources/${resourceName}/${id}`)
                if (response.status == 'success') {
                    this.$emit('update')
                }
            }
        },
    }
}
</script>
