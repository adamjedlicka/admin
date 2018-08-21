<template>
    <div>
        <input
            type="text"
            :value="model"
            @input="onInput"
            class="border border-grey rounded-lg py-2 px-4 outline-none focus:shadow-outline" >
    </div>
</template>

<script>
export default {
    props: {
        field: Object,
        model: null,
    },

    methods: {
        onInput(e) {
            this.$emit('input', this.field.name, e.target.value)
        }
    }
}
</script>
