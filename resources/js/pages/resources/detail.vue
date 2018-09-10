<template>
    <ResourceDetail v-if="resource"
        :resource="resource"
        :value="model"
        :title="resource.title"
        action="detail" >

        <template slot="buttons">

            <router-link v-if="resource.policies.update"
                :to="`/resources/${resource.name}/${resource.key}/edit`"
                class="btn btn-blue" >
                Edit
            </router-link>

            <a v-if="resource.policies.delete"
                @click="onDelete"
                class="btn btn-red" >
                Delete
            </a>

        </template>

    </ResourceDetail>
</template>

<script>
import HasResource from './HasResource'

export default {
    mixins: [
        HasResource,
    ],

    computed: {
        source() {
            let resource = this.$route.params.resource
            let resourceKey = this.$route.params.resourceKey

            return `/api/resources/${resource}/${resourceKey}`
        }
    },

    methods: {
        async onDelete() {
            let ok = await modalConfirm('Delete', `Delete this record: ${this.resource.title} ?`, true)
            if (!ok) return

            let response = await this.$delete(`/api/resources/${this.resource.name}/${this.resource.key}`)
            if (response.status == 'success') {
                this.$router.push(`/resources/${this.resource.name}`)
            }
        },
    }
}
</script>
