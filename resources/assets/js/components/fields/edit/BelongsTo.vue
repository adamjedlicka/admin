<template>
    <div>

        <select @input="onInput" :value="model"
            class="bg-white border border-grey rounded-lg py-2 px-4 outline-none focus:shadow-outline"
            :disabled="disabled" >

            <option :value="null"></option>

            <option v-for="(resource, i) in resources" :key="i"
                :value="resource.key"
                :selected="resource.key == model" >
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
        model: null,
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
            this.$emit('input', this.field.name, e.target.value)
        }
    },

    async mounted() {
        if (this.field.value) {
            this.resources = [{
                key: this.field.value.key,
                title: this.field.value.title,
            }]
        }

        let response = await this.$get(this.field.meta.source)
        this.resources = response.resources
    }
}
</script>
