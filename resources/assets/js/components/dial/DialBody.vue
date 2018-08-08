<template>
    <div class="bg-white">

        <div v-for="(row, i) in rows" :key="i"
            class="flex hover:bg-grey-lighter" >

            <div v-for="(field, j) in fields" :key="j"
                class="font-thin text-lg text-grey-darkest p-4"
                :class="[$parent.fieldWidth(field)]" >
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
    },

    computed: {
        rows() {
            return this.resource.data.data
        }
    },
}
</script>
