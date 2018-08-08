<template>
    <div class="flex justify-between bg-blue-lightest border-t border-grey p-4">

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
        current: Number,
        last: Number,
    },

    computed: {
        classesPrevious() {
            return this.current == 1
                ? this.classesDisabled
                : this.classesEnabled
        },

        classesNext() {
            return this.current == this.last
                ? this.classesDisabled
                : this.classesEnabled
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
            if (this.current == this.last) return

            this.$emit('page', this.current + 1)
        },

        previous() {
            if (this.current == 1) return

            this.$emit('page', this.current - 1)
        }
    }
}
</script>
