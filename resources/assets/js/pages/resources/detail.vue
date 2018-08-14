<template>
    <div v-if="value">

        <Panel
            :displayName="value.resource.title"
            :fields="value.fields"
            :resource="value.resource"
            action="detail" >

            <div slot="title" class="flex">
                <h1 class="h1 pb-4">
                    <router-link :to="indexUrl"
                        class="no-underline text-blue hover:text-blue-dark" >
                        Index
                    </router-link>

                    <span class="text-lg">
                        /
                    </span>

                    <span>
                        {{ value.resource.title }}
                    </span>
                </h1>
            </div>

        </Panel>

        <Panel v-for="(panel, i) in value.panels" :key="i"
            :displayName="panel.displayName"
            :fields="panel.fields"
            :resource="value.resource"
            action="detail" >

            <h2 class="h2 pb-4" slot="title">{{ panel.displayName }}</h2>

        </Panel>

    </div>
</template>

<script>
export default {
    data() {
        return {
            value: null,
        }
    },

    computed: {
        indexUrl() {
            let resourceName = this.$route.params.resource

            return `/resources/${resourceName}`
        },

        editUrl() {
            let resourceName = this.$route.params.resource
            let id = this.$route.params.id

            return `/resources/${resourceName}/${id}/edit`
        },
    },

    mounted() {
        this.fetchData()
    },

    methods: {
        async fetchData() {
            let resourceName = this.$route.params.resource
            let id = this.$route.params.id

            this.value = await this.$get(`/api/resources/${resourceName}/${id}`)
        }
    }
}
</script>
