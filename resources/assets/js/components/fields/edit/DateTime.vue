<template>
    <div>
        <input
            type="text"
            :value="display"
            class="border border-grey rounded-lg py-2 px-4 outline-none focus:shadow-outline" >
    </div>
</template>

<script>
export default {
    props: {
        value: null,
    },

    computed: {
        display() {
            if (!this.value) return null

            return new Date(this.value.date).toLocaleString(locale())
        }
    }
}
</script>
