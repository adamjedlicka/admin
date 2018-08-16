<template>
    <tbody>

        <DialBodyRow v-for="(resource, i) in resources" :key="i"
            class="hover:bg-grey-lighter"
            :resource="resource" />

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
            let resourceName = this.$route.params.resource
            let id = resource.key

            return `/resources/${resourceName}/${id}`
        },

        editUrl(resource) {
            return this.detailUrl(resource) + '/edit'
        },

        async onDelete(resource) {
            let resourceName = this.$route.params.resource
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
