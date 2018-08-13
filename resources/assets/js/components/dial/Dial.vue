<template>
    <div v-if="value"
        class="bg-white shadow-md rounded-lg min-w-100" >

        <div class="rounded-t-lg overflow-x-auto">
            <table class="w-full">

                <DialHeader :value="value" :fields="value.fields"
                    @sort="onSort" />

                <DialBody :value="value" :fields="value.fields"
                    @update="fetchData" />

            </table>
        </div>

        <DialPagination
            :currentPage="value.data.pagination.currentPage"
            :hasPreviousPage="value.data.pagination.hasPreviousPage"
            :hasNextPage="value.data.pagination.hasNextPage"
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
            value: null,
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
            this.value = await this.$get(this.url)
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
