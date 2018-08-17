<template>
    <thead
        class="bg-grey-lighter rounded-lg" >

        <tr class="border-b-2" >

            <th v-for="(field, i) in fields" :key="i"
                class="p-4 font-bold text-lg text-grey-darker text-left"
                :class="classes(field)"
                @click="onClick(field)" >

                <div class="truncate">
                    <span v-if="field.isSortable">
                        <i v-if="sort != field.name" class="fas fa-sort"></i>
                        <i v-if="sort == field.name && order == 'asc'" class="fas fa-sort-up"></i>
                        <i v-if="sort == field.name && order == 'desc'" class="fas fa-sort-down"></i>
                    </span>

                    {{ field.displayName }}
                </div>

            </th>

            <!-- CRUD buttons -->
            <th style="width: 100%"></th>

        </tr>

    </thead>
</template>

<script>
export default {
    props: {
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
            if (!field.isSortable) return

            if (field.name == this.sort) {
                this.sort = field.name
                this.order = {
                    null: 'asc',
                    asc: 'desc',
                    desc: null,
                }[this.order]
            } else {
                this.sort = field.name
                this.order = 'asc'
            }

            if (this.order == null) {
                this.sort = null
            }

            this.$emit('sort', this.sort, this.order)
        },

        classes(field) {
            return [
                field.isSortable ? 'cursor-pointer' : '',
            ]
        },
    }
}
</script>
