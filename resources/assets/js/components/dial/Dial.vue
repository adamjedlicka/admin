<template>
    <div v-if="dial"
        class="bg-white shadow-md rounded-lg min-w-100" >

        <div class="rounded-t-lg overflow-x-auto">
            <table class="w-full">

                <DialHeader
                    :prefix="name"
                    :fields="dial.fields"
                    @sort="onSort" />

                <DialBody
                    :rows="dial.data"
                    :links="dial.links" >

                    <template slot="buttons" slot-scope="scope">
                        <slot name="buttons" :resource="scope.resource">
                            <router-link v-if="scope.resource.policies.view" :to="detailUrl(scope.resource)"
                                title="Detail"
                                class="text-grey hover:text-black cursor-pointer" >
                                <i class="py-4 px-1 far fa-eye"></i>
                            </router-link>

                            <router-link v-if="scope.resource.policies.update" :to="editUrl(scope.resource)"
                                title="Edit"
                                class="text-grey hover:text-black cursor-pointer" >
                                <i class="py-4 px-1 far fa-edit"></i>
                            </router-link>

                            <a v-if="scope.resource.policies.delete" @click="onDelete(scope.resource)"
                                title="Detail"
                                class="text-grey hover:text-red cursor-pointer" >
                                <i class="py-4 px-1 far fa-trash-alt"></i>
                            </a>
                        </slot>
                    </template>

                </DialBody>

            </table>
        </div>

        <DialPagination
            :pagination="dial.pagination"
            @page="onPageChange" />

    </div>
</template>

<script>
import DialPagination from './DialPagination'
import DialHeader from './DialHeader'
import DialBody from './DialBody'
import Url from '~/support/Url'

export default {
    props: [
        'source',
        'name',
    ],

    data() {
        return {
            dial: null,
            url: null,
        }
    },

    mounted() {
        this.url = new Url(this.source)

        this.fetchData()
    },

    methods: {
        onPageChange(page) {
            this.url.page = page
            this.fetchData()
        },

        onSort(sort, order) {
            this.url.sortBy = sort
            this.url.orderBy = order
            this.fetchData()
        },

        async fetchData() {
            this.dial = await this.$get(this.url)
                .syncQueryString(this.name)

            this.$emit('update', this.dial)
        },

        detailUrl(resource) {
            return `/resources/${resource.name}/${resource.key}`
        },

        editUrl(resource) {
            return `/resources/${resource.name}/${resource.key}/edit`
        },

        deleteUrl(resource) {
            return `/api/resources/${resource.name}/${resource.key}`
        },

        async onDelete(resource) {
            let ok = await modalConfirm('Delete', `Delete ${resource.title} ?`, true)
            if (!ok) return

            let response = await this.$delete(this.deleteUrl(resource))
            if (response.status == 'success') this.fetchData()

        },
    },

    components: {
        DialPagination,
        DialHeader,
        DialBody,
    }
}
</script>
