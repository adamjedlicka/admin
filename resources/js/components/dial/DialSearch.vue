<template>
    <div class="flex px-4 pt-4 bg-grey-lighter rounded-lg">
        <input
            type="text"
            placeholder="Search..."
            class="border border-grey rounded-lg py-2 px-4 outline-none focus:shadow-outline"
            :value="value"
            @input="onInput" >
    </div>
</template>

<script>
import Url from '~/support/Url'
import debounce from "lodash/debounce"

export default {
    props: [
        'value',
    ],

    methods: {
        onInput: debounce(function (e) {
            let value = e.target.value

            // Convert empty string to null to clear URL query
            if (!value) value = null

            this.$emit('search', value)
        }, 500)
    }
}
</script>
