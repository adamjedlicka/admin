<template>
    <div v-if="resource">

        <select @input="onInput" :value="value"
            class="bg-white border border-grey rounded-lg py-2 px-4 outline-none focus:shadow-outline w-96 max-w-full"
            :disabled="cannotBeChanged" >

            <option :value="null"></option>

            <option v-for="resource in resource.data" :key="resource.key"
                :value="resource.key"
                :selected="resource.key == value" >
                {{ resource.title }}
            </option>

        </select>

    </div>
</template>

<script>
import Url from '~/support/Url'

export default {
    props: {
        field: Object,
        value: null,
    },

    data() {
        return {
            cannotBeChanged: false,
            resource: null,
        }
    },

    async mounted() {
        if (this.value) {
            this.cannotBeChanged = this.field.isUnchangeable
        }

        this.fetchData()
    },

    methods: {
        async fetchData() {
            let relatedResourceName = this.field.exports.relatedResourceName

            this.resource = await this.$get(`/api/resources/${relatedResourceName}`)

            let via = new Url().object('via')[this.field.name]
            if (via) {
                this.cannotBeChanged = true
                this.$emit('input', via)
            }
        },

        onInput(e) {
            this.$emit('input', e.target.value)
        }
    },
}
</script>
