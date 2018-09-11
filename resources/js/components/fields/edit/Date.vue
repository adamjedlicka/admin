<template>
    <div>
        <input
            ref="input"
            type="text"
            :value="value"
            @input="onInput"
            :disabled="field.isUnchangeable"
            class="border border-grey rounded-lg py-2 px-4 outline-none focus:shadow-outline w-96 max-w-full" >
    </div>
</template>

<script>
import flatpickr from 'flatpickr'

export default {
    props: {
        field: Object,
        value: null,
    },

    mounted() {
        flatpickr(this.$refs.input, {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd. m. Y'
        })
    },

    methods: {
        onInput(e) {
            this.$emit('input', e.target.value)
        }
    }
}
</script>
