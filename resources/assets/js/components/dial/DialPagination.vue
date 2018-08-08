<template>
    <div class="flex justify-between bg-blue-lightest border-t border-grey p-4">

        <div class="font-bold text-grey-darker no-underline"
            @click="previous" >
            Previous
        </div>

        <div class="font-bold text-grey-darker no-underline"
            @click="next" >
            Next
        </div>

    </div>
</template>

<script>
export default {
    props: {
        current: Number,
    },

    methods: {
        next() {
            this.$emit('page', this.current + 1)
        },

        previous() {
            this.$emit('page', this.current - 1)
        }
    }
}
</script>
