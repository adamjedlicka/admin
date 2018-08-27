<template>
    <div v-if="field"
        class="flex p-4">

        <div class="text-md text-grey-dark font-bold w-1/3 md:w-1/6">
            {{ field.displayName }}
        </div>

        <div class="text-grey-darkest w-2/3 md:w-5/6">
            <component :is="`${field.type}-${action}-field`"
                :field="field"
                :value="value"
                @input="onInput" />

            <div>
                <p v-for="(error, i) in errors" :key="i"
                    class="text-sm text-red pt-2" >
                    {{ error }}
                </p>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    props: [
        'field',
        'value',
        'errors',
        'action',
    ],

    methods: {
        onInput(value) {
            this.$emit('input', value)
        }
    }
}
</script>
