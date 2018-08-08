<template>
    <div class="flex justify-between bg-blue-lightest border-t border-grey p-4">

        <a href="#"
            class="font-bold text-grey-darker no-underline" >
            Previous
        </a>

        <a href="#"
            class="font-bold text-grey-darker no-underline" >
            Next
        </a>

    </div>
</template>

<script>
export default {
    props: {
        current: Number,
        total: Number,
        perPage: Number,
    },

    computed: {
        pages() {
            let numOfPages = Math.ceil(this.total / this.perPage)
            return [...Array(numOfPages).keys()]
        }
    }
}
</script>
