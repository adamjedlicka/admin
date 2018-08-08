<template>
    <div v-if="resource"
        class="bg-white shadow-md rounded-lg min-w-100 overflow-auto" >

        <DialHeader :resource="resource" :fields="fields" />

        <DialBody :resource="resource" :fields="fields" />

        <DialPagination
            :current="resource.data.current_page"
            :last="resource.data.last_page"
            @page="onPageChange" />

    </div>
</template>

<script>
import DialPagination from './DialPagination'
import DialHeader from './DialHeader'
import DialBody from './DialBody'
import Url from '~/util/Url'

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

    computed: {
        fields() {
            return this.resource.fields.filter(field => field.indexVisible)
        }
    },

    methods: {
        onPageChange(page) {
            this.url.page = page
            this.fetchData()
        },

        async fetchData() {
            this.resource = await this.$getSync(this.url)
        },

        fieldWidth(field) {
            switch (field.indexSize) {
                case 'small':
                    return 'w-16'
                    break
                default:
                    return 'flex-1'
                    break
            }
        }
    },

    components: {
        DialPagination,
        DialHeader,
        DialBody,
    }
}
</script>
