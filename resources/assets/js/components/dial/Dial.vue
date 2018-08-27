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
                    :links="dial.links"
                    @update="fetchData" >

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
    },

    components: {
        DialPagination,
        DialHeader,
        DialBody,
    }
}
</script>
