<template>
    <div id="dial-header" class="flex bg-grey-lighter border-b-2 border-grey">

        <div
            v-for="(field, i) in fields" :key="i"
            id="dial-header-field"
            class="font-bold text-grey-darker p-4"
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
