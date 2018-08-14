<template>
    <div>

        <select @input="onInput" :value="field.value ? field.value.key : null"
            class="bg-white border border-grey rounded-lg py-2 px-4 outline-none focus:shadow-outline" >

            <option :value="null"></option>

            <option v-for="(resource, i) in field.meta" :key="i"
                :value="resource.key" >
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

    methods: {
        onInput(e) {
            this.$emit('input', this.field.name, {
                key: e.target.value
            })
        }
    }
}
</script>
