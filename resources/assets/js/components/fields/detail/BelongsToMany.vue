<template>
    <Panel :title="field.displayName">

        <template slot="buttons">
            <router-link v-if="field.exports.policies.attachAny"
                :to="attachUrl"
                class="btn btn-blue" >
                Attach
            </router-link>
        </template>

        <template slot="body">
            <Dial ref="dial" :source="source" :name="field.name">

                <template slot="buttons" slot-scope="scope">
                    <a v-if="scope.resource.policies.detach" @click="onDetach(scope.resource)"
                        title="Detach"
                        class="text-grey hover:text-red cursor-pointer" >
                        <i class="py-4 px-1 fas fa-unlink"></i>
                    </a>
                </template>

            </Dial>
        </template>

    </Panel>
</template>

<script>
export default {
    props: {
        field: Object,
    },

    computed: {
        source() {
            let resource = this.$route.params.resource
            let resourceKey = this.$route.params.resourceKey
            let relationship = this.field.name

            return `/api/resources/${resource}/${resourceKey}/belongsToMany/${relationship}`
        },

        attachUrl() {
            let resource = this.$route.params.resource
            let resourceKey = this.$route.params.resourceKey
            let relationship = this.field.name

            return `/resources/${resource}/${resourceKey}/attach/${relationship}`
        },
    },

    methods: {
        async onDetach(resource) {
            let ok = await modalConfirm('Detach', `Detach this record: ${resource.title} ?`, true)
            if (!ok) return

            let response = await this.$delete(`${this.source}/${resource.key}`)

            if (response.status == 'success') {
                this.$refs.dial.fetchData()
            }
        },
    }
}
</script>
