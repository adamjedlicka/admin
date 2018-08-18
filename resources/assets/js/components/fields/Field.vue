<template>
    <div v-if="field.visibleOn.includes(action)"
        class="flex p-4">

        <div class="text-md text-grey-dark font-bold w-1/6">
            {{ field.displayName }}
        </div>

        <div class="w-5/6 text-grey-darkest">
            <component :is="`${field.type}-${action}-field`"
                :field="field"
                :model="model"
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
    props: {
        field: Object,
        model: null,
        errors: Array,
        action: String,
    },

    methods: {
        onInput(...args) {
            this.$emit('input', ...args)
        }
    }
}
</script>
