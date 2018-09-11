<template>
    <div>
        <input
            ref="input"
            type="text"
            :value="display"
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

    computed: {
        display() {
            return new Date(this.value).toLocaleString(document.documentElement.lang)
        },
    },

    mounted() {
        flatpickr(this.$refs.input, {
            enableTime: true,
            dateFormat: 'Z',
            time_24hr: true,
            altInput: true,
            altFormat: 'd. m. Y H:i:S'
        })
    },

    methods: {
        onInput(e) {
            this.$emit('input', e.target.value)
        }
    }
}
</script>
