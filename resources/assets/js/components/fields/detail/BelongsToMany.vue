<template>
    <div class="p-4">

        <div class="flex justify-between pb-4">
            <div>
                <h2 class="h2">{{ field.displayName }}</h2>
            </div>

            <div>
                <router-link :to="attachUrl" class="btn btn-blue">
                    Attach
                </router-link>
            </div>
        </div>

        <div class="panel">

            <Dial
                ref="dial"
                :source="source"
                :prefix="field.name" >

                <template slot="buttons" slot-scope="scope">
                    <router-link :to="detailUrl(scope.resource)"
                        title="Detail"
                        class="text-grey hover:text-black cursor-pointer" >
                        <i class="py-4 px-1 far fa-eye"></i>
                    </router-link>

                    <span class="text-grey hover:text-red cursor-pointer"
                        title="Detach"
                        @click="onDetach(scope.resource)" >
                        <i class="py-4 px-1 fas fa-unlink"></i>
                    </span>
                </template>

            </Dial>

        </div>

    </div>
</template>

<script>
export default {
    props: {
        field: Object,
    },

    computed: {
        resource() { return this.$route.params.resource },
        key() { return this.$route.params.key },
        relationship() { return this.field.name },

        source() {
            return `/api/resources/${this.resource}/${this.key}/belongsToMany/${this.relationship}`
        },

        attachUrl() {
            return `/resources/${this.resource}/${this.key}/attach/${this.relationship}`
        }
    },

    methods: {
        detailUrl(resource) {
            let resourceName = resource.name
            let id = resource.key

            return `/resources/${resourceName}/${id}`
        },

        async onDetach(resource) {
            let ok = await modalConfirm('Detach', 'Detach this record?', true)
            if (!ok) return

            let response = await this.$delete(`/api/resources/${this.resource}/${this.key}/belongsToMany/${this.relationship}/detach/${resource.key}`)
            if (response.status == 'success') {
                this.$refs.dial.fetchData()
            }
        }
    }
}
</script>
