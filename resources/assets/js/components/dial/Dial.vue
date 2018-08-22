<template>
    <div v-if="index"
        class="bg-white shadow-md rounded-lg min-w-100" >

        <div class="rounded-t-lg overflow-x-auto">
            <table class="w-full">

                <DialHeader
                    :prefix="prefix"
                    :fields="fields"
                    @sort="onSort" />

                <DialBody
                    :resources="resources"
                    @update="fetchData" >

                    <template v-if="!!$scopedSlots.buttons" slot="buttons" slot-scope="scope">
                        <slot name="buttons" :resource="scope.resource" />
                    </template>

                </DialBody>

            </table>
        </div>

        <DialPagination
            :currentPage="index.pagination.currentPage"
            :hasPreviousPage="index.pagination.hasPreviousPage"
            :hasNextPage="index.pagination.hasNextPage"
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
        prefix: String,
        source: String,
    },

    data() {
        return {
            index: null,
            url: null,
        }
    },

    computed: {
        fields() {
            return this.index.fields
        },

        resources() {
            return this.index.resources
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
            this.index = await this.$get(this.url)
                .syncQueryString(this.prefix)

            this.$emit('update', this.index)
        },
    },

    components: {
        DialPagination,
        DialHeader,
        DialBody,
    }
}
</script>
