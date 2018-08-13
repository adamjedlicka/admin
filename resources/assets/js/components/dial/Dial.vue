<template>
    <div v-if="resource"
        class="bg-white shadow-md rounded-lg min-w-100" >

        <div class="rounded-lg overflow-x-auto">
            <table class="w-full">

                <DialHeader :resource="resource" :fields="resource.fields"
                    @sort="onSort" />

                <DialBody :resource="resource" :fields="resource.fields"
                    @update="fetchData" />

            </table>
        </div>

        <DialPagination
            :currentPage="resource.data.pagination.currentPage"
            :hasPreviousPage="resource.data.pagination.hasPreviousPage"
            :hasNextPage="resource.data.pagination.hasNextPage"
            @page="onPageChange" />

    </div>
</template>

<script>
import DialPagination from './DialPagination'
import DialHeader from './DialHeader'
import DialBody from './DialBody'
import Url from '~/support/Url'

export default {
    props: {
        source: String,
    },

    data() {
        return {
            resource: null,
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
            this.resource = await this.$get(this.url)
                .syncQueryString()
        },
    },

    components: {
        DialPagination,
        DialHeader,
        DialBody,
    }
}
</script>
