<template>
    <Panel :displayName="'Attach'">

        <div slot="buttons" class="buttons">
            <a class="btn btn-green" @click="attach">Attach</a>
        </div>

        <div slot="body">
            <Field v-if="relatedResource"
                :field="field"
                :model="key"
                action="edit"
                @input="onKeyInput" />

            <Field v-for="field in fields" :key="field.name"
                :field="field"
                :model="pivot[field.name]"
                action="edit"
                @input="onInput" />
        </div>
    </Panel>
</template>

<script>
export default {
    data() {
        return {
            relatedResource: null,
            fields: [],

            key: null,
            pivot: {},
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
        this.fields = response.fields
    },

    methods: {
        async attach() {
            let response = await this.$post(`${this.source}/${this.key}`, this.pivot)

            if (response.status == 'success') {
                let resource = this.$route.params.resource
                let key = this.$route.params.key

                this.$router.push(`/resources/${resource}/${key}`)
            }
        },

        onKeyInput(name, value) {
            this.key = value
        },

        onInput(name, value) {
            this.pivot[name] = value
        }
    }
}
</script>
