<template>
    <div>

        <div v-for="(row, i) in resource.data.data" :key="i"
            class="flex p-2 hover:bg-grey-lighter" >

            <div v-for="(field, j) in fields" :key="j"
                class="flex-1" >
                {{ row[field.field] }}
            </div>

        </div>

    </div>
</template>

<script>
export default {
    props: {
        resource: Object,
        fields: Array,

    }
}
</script>
