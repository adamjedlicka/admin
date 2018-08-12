<template>
    <thead
        class="bg-grey-lighter rounded-t-lg" >

        <tr
            class="border-b-2" >

            <th v-for="(field, i) in fields" :key="i"
                class="p-4 font-bold text-lg text-grey-darker text-left truncate"
                :class="classes(field)"
                @click="onClick(field)" >

                {{ field.name }}

                <span v-if="field.sortable">
                    <i v-if="sort != field.field" class="fas fa-sort"></i>
                    <i v-if="sort == field.field && order == 'asc'" class="fas fa-sort-up"></i>
                    <i v-if="sort == field.field && order == 'desc'" class="fas fa-sort-down"></i>
                </span>

            </th>

            <!-- CRUD buttons -->
            <th class="">
            </th>

        </tr>

    </thead>
</template>

<script>
export default {
    props: {
        resource: Object,
        fields: Array,
    },

    data() {
        return {
            sort: this.$route.query.sortBy,
            order: this.$route.query.orderBy,
        }
    },

    methods: {
        onClick(field) {
            if (!field.sortable) return

            if (field.field == this.sort) {
                this.sort = field.field
                this.order = {
                    null: 'asc',
                    asc: 'desc',
                    desc: null,
                }[this.order]
            } else {
                this.sort = field.field
                this.order = 'asc'
            }

            if (this.order == null) {
                this.sort = null
            }

            this.$emit('sort', this.sort, this.order)
        },

        classes(field) {
            return [
                this.fieldWidth(field),
                field.sortable ? 'cursor-pointer' : '',
            ]
        },

        fieldWidth(field) {
            switch (field.indexSize) {
                case 'small':
                    return 'w-16'
                    break
                default:
                    return 'w-32'
                    break
            }
        }
    }
}
</script>
