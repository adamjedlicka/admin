<template>
    <div>

        <select @input="onInput" :value="field.value"
            class="bg-white border border-grey rounded-lg py-2 px-4 outline-none focus:shadow-outline" >

            <option :value="null"></option>

            <option v-for="(resource, i) in resources" :key="i"
                :value="resource.key"
                :selected="resource.key == field.value" >
                {{ resource.title }}
            </option>

        </select>

    </div>
</template>

<script>
export default {
    props: {
        field: Object,
    },

    data() {
        return {
            resources: [],
        }
    },

    methods: {
        onInput(e) {
            this.$emit('input', this.field.name, e.target.value)
        }
    },

    async mounted() {
        if (this.field.meta.value) {
            this.resources = [{
                key: this.field.meta.value.key,
                title: this.field.meta.value.title,
            }]
        }

        let response = await this.$get(this.field.meta.info.source)
        this.resources = response.resources
    }
}
</script>
