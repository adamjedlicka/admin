<template>
    <div>
        <input
            type="text"
            :value="value"
            @input="onInput"
            class="border border-grey rounded-lg py-2 px-4 outline-none focus:shadow-outline" >
    </div>
</template>

<script>
export default {
    props: {
        value: null,
    },

    methods: {
        onInput(e) {
            this.$emit('input', e.target.value)
        }
    }
}
</script>
