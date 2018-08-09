<template>
    <div>
        <input
            type="text"
            :value="value"
            @input="onInput"
            class="border border-grey rounded-lg py-2 px-4 outline-none focus:shadow-outline"
            :class="{'border-red' : !valid}" >

        <div v-for="(error, i) in errors" :key="i"
            class="text-sm text-red pt-2" >
            {{ error }}
        </div>
    </div>
</template>

<script>
import HasValidation from '../HasValidation'

export default {
    mixins: [
        HasValidation
    ],

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
