<template>
    <div id="dial-header" class="flex bg-grey-lighter border-b-2 border-grey">

        <div
            v-for="(field, i) in fields" :key="i"
            id="dial-header-field"
            class="font-bold text-grey-darker p-4"
            :class="classes(field)"
            @click="onClick(field)" >

            {{ field.name }}

            <span v-if="field.sortable">
                <i v-if="sort != field.field" class="fas fa-sort"></i>
                <i v-if="sort == field.field && order == 'asc'" class="fas fa-sort-up"></i>
                <i v-if="sort == field.field && order == 'desc'" class="fas fa-sort-down"></i>
            </span>

        </div>

        <div class="w-24">
        </div>

    </div>
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
                this.$parent.fieldWidth(field),
                field.sortable ? 'cursor-pointer' : '',
            ]
        },
    }
}
</script>
