<template>
    <tbody>

        <DialBodyRow v-for="(resource, i) in resources" :key="i"
            class="hover:bg-grey-lighter"
            :resource="resource"
            @update="$emit('update')" >

            <template v-if="!!$scopedSlots.buttons" slot="buttons" slot-scope="scope">
                <slot name="buttons" :resource="scope.resource">
                    <router-link :to="detailUrl(resource)"
                        title="Detail"
                        class="text-grey hover:text-black cursor-pointer" >
                        <i class="py-4 px-1 far fa-eye"></i>
                    </router-link>

                    <router-link :to="editUrl(resource)"
                        title="Edit"
                        class="text-grey hover:text-black cursor-pointer" >
                        <i class="py-4 px-1 far fa-edit"></i>
                    </router-link>

                    <span class="text-grey hover:text-red cursor-pointer"
                        title="Delete"
                        @click="onDelete(resource)" >
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
        resources: Array,
    },

    methods: {
        detailUrl(resource) {
            let resourceName = resource.name
            let id = resource.key

            return `/resources/${resourceName}/${id}`
        },

        editUrl(resource) {
            return this.detailUrl(resource) + '/edit'
        },

        async onDelete(resource) {
            let resourceName = resource.name
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

    components: {
        DialBodyRow,
    }
}
</script>
