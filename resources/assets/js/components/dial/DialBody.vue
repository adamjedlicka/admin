<template>
    <div class="bg-white">

        <div v-for="(row, i) in rows" :key="i"
            class="flex hover:bg-grey-lighter" >

            <div v-for="(field, j) in fields" :key="j"
                class="font-thin text-lg text-grey-darkest p-4"
                :class="[$parent.fieldWidth(field)]" >

                <component :is="`${field.type}-index-field`" :value="row.attributes[field.field]" />

            </div>

            <div class="flex justify-end pr-4 w-24">
                <router-link :to="detailUrl(row)"
                    class="text-grey hover:text-black cursor-pointer" >
                    <i class="py-4 px-1 far fa-eye"></i>
                </router-link>

                <span class="text-grey hover:text-black cursor-pointer">
                    <i class="py-4 px-1 far fa-edit"></i>
                </span>

                <span class="text-grey hover:text-red cursor-pointer">
                    <i class="py-4 px-1 far fa-trash-alt"></i>
                </span>
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
            return this.resource.data.rows
        }
    },

    methods: {
        detailUrl(row) {
            let resourceName = this.$route.params.resource
            let id = row.attributes.id

            return `/resources/${resourceName}/${id}`
        }
    }
}
</script>
