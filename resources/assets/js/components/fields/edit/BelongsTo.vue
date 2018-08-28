<template>
    <div>

        <select @input="onInput" :value="value"
            class="bg-white border border-grey rounded-lg py-2 px-4 outline-none focus:shadow-outline w-96 max-w-full"
            :disabled="cannotBeChanged" >

            <option :value="null"></option>

            <option v-if="cannotBeChanged" :value="value" selected>{{ field.meta.title }}</option>

            <option v-for="resource in resources" :key="resource.key"
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
            resources: [],
        }
    },

    computed: {
        disabled() {
            return !!new Url().object('via')[this.field.name]
        }
    },


    async mounted() {
        if (this.value) {
            this.cannotBeChanged = this.field.isUnchangeable
        }

        if (this.cannotBeChanged) return

        this.resources = await this.$get(this.field.exports.source)
    },

    methods: {
        onInput(e) {
            this.$emit('input', e.target.value)
        }
    },
}
</script>
