<template>
    <div v-if="field"
        class="flex p-4">

        <div class="text-md text-grey-dark font-bold w-1/6">
            {{ field.displayName }}
        </div>

        <div class="w-5/6 text-grey-darkest">
            <component :is="`${field.type}-${action}-field`"
                v-model="value"
                :field="field" />

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
        value: null,
        errors: Array,
        action: String,
    }
}
</script>
