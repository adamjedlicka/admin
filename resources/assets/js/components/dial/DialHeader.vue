<template>
    <div class="flex p-4">

        <div
            v-for="(field, i) in fields" :key="i"
            class="flex-1 text-xl" >
            {{ field.name }}
        </div>

    </div>
</template>

<script>
export default {
    props: {
        resource: Object,
        fields: Array,
    },
}
</script>
