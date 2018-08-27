<template>
    <div>

        <select @input="onInput" :value="value"
            class="bg-white border border-grey rounded-lg py-2 px-4 outline-none focus:shadow-outline w-96 max-w-full"
            :disabled="disabled" >

            <option :value="null"></option>

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
            resources: [],
        }
    },

    computed: {
        disabled() {
            return !!new Url().object('via')[this.field.name]
        }
    },

    methods: {
        onInput(e) {
            this.$emit('input', e.target.value)
        }
    },

    async mounted() {
        this.resources = await this.$get(this.field.meta.source)
    }
}
</script>
