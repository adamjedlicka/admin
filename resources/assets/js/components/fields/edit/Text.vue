<template>
    <input
        type="text"
        :value="value"
        class="border border-grey rounded-lg py-2 px-4 outline-none focus:shadow-outline" >
</template>

<script>
export default {
    props: {
        value: null,
    }
}
</script>
