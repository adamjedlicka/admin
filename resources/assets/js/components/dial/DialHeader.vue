<template>
    <div class="flex p-4 bg-grey-lighter border-b-2 border-grey">

        <div
            v-for="(field, i) in fields" :key="i"
            class="font-bold text-grey-darker"
            :class="[$parent.fieldWidth(field)]" >
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
