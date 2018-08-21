<template>
    <div class="p-4">

        <div class="flex justify-between pb-4">
            <div>
                <h2 class="h2">{{ field.displayName }}</h2>
            </div>

        </div>

        <div class="panel">

            <Field v-for="(field, i) in field.meta.fields" :key="i"
                :field="field"
                action="detail" />

        </div>

    </div>
</template>

<script>
export default {
    props: {
        field: Object,
    },
}
</script>
