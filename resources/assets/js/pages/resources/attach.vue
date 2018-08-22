<template>
    <Panel :displayName="'Attach'">

        <div slot="buttons" class="buttons">
            <a class="btn btn-green" @click="attach">Attach</a>
        </div>

        <Field v-if="relatedResource" slot="body"
            :field="field"
            :model="key"
            action="edit"
            @input="onInput" />

    </Panel>
</template>

<script>
export default {
    data() {
        return {
            relatedResource: null,

            key: null,
        }
    },

    computed: {
        source() {
            let resource = this.$route.params.resource
            let key = this.$route.params.key
            let what = this.$route.params.what

            return `/api/resources/${resource}/${key}/belongsToMany/${what}/attach`
        },

        field() {
            return {
                type: 'BelongsTo',
                name: this.relatedResource,
                displayName: this.relatedResource,
                meta: {
                    source: `/api/resources/${this.relatedResource}`
                }
            }
        }
    },

    async mounted() {
        let response = await this.$get(this.source)

        this.relatedResource = response.relatedResource
    },

    methods: {
        async attach() {
            let response = await this.$post(this.source, {
                key: this.key,
            })

            if (response.status == 'success') {
                let resource = this.$route.params.resource
                let key = this.$route.params.key

                this.$router.push(`/resources/${resource}/${key}`)
            }
        },

        onInput(name, value) {
            this.key = value
        }
    }
}
</script>
