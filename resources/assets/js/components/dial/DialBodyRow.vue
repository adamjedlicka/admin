<template>
    <tr
        class="hover:bg-grey-lighter" >

        <td v-for="(field, j) in fields" :key="j"
            class="p-4 text-black text-left" >

            <div class="truncate">
                <component
                    :is="`${field.type}-index-field`"
                    :field="field" />
            </div>

        </td>

        <!-- CRUD buttons -->
        <td class="text-right pr-2 whitespace-no-wrap">
            <router-link :to="detailUrl(resource)"
                class="text-grey hover:text-black cursor-pointer" >
                <i class="py-4 px-1 far fa-eye"></i>
            </router-link>

            <router-link :to="editUrl(resource)"
                class="text-grey hover:text-black cursor-pointer" >
                <i class="py-4 px-1 far fa-edit"></i>
            </router-link>

            <span class="text-grey hover:text-red cursor-pointer"
                @click="onDelete(resource)" >
                <i class="py-4 px-1 far fa-trash-alt"></i>
            </span>
        </td>

    </tr>
</template>

<script>
export default {
    props: {
        resource: Object
    },

    computed: {
        fields() {
            return this.resource.fields.filter(field => field.visibleOn.includes('index'))
        }
    },

    methods: {
        detailUrl(resource) {
            let resourceName = resource.name.toLowerCase()
            let id = resource.key

            return `/resources/${resourceName}/${id}`
        },

        editUrl(resource) {
            return this.detailUrl(resource) + '/edit'
        },

        async onDelete(resource) {
            let resourceName = resource.name.toLowerCase()
            let id = resource.key

            let ok = await modalConfirm('Delete', 'Delete this record?', true)
            if (ok) {
                let response = await this.$delete(`/api/resources/${resourceName}/${id}`)
                if (response.status == 'success') {
                    this.$emit('update')
                }
            }
        },
    },
}
</script>
