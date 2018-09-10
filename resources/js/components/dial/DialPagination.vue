<template>
    <div class="flex justify-between bg-blue-lightest border-t border-grey p-4 rounded-b-lg">

        <div class="font-bold no-underline select-none"
            :class="classesPrevious"
            @click="previous" >
            Previous
        </div>

        <div class="font-bold no-underline select-none"
            :class="classesNext"
            @click="next" >
            Next
        </div>

    </div>
</template>

<script>
export default {
    props: {
        currentPage: Number,
        hasPreviousPage: Boolean,
        hasNextPage: Boolean,
    },

    computed: {
        classesPrevious() {
            return this.hasPreviousPage
                ? this.classesEnabled
                : this.classesDisabled
        },

        classesNext() {
            return this.hasNextPage
                ? this.classesEnabled
                : this.classesDisabled
        },

        classesEnabled() {
            return [
                'text-grey-darker',
                'cursor-pointer',
            ]
        },

        classesDisabled() {
            return [
                'text-grey',
                'cursor-not-allowed',
            ]
        },
    },

    methods: {
        next() {
            if (!this.hasNextPage) return

            this.$emit('page', this.currentPage + 1)
        },

        previous() {
            if (!this.hasPreviousPage) return

            this.$emit('page', this.currentPage - 1)
        }
    }
}
</script>
