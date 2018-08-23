<template>
    <div class="p-4">
        <div class="flex justify-between pb-4">
            <slot name="title">
                <h1 class="h1">{{ displayName }}</h1>
            </slot>

            <div class="buttons">
                <slot name="buttons" />
            </div>
        </div>

        <div class="panel">
            <slot name="body" />
        </div>
    </div>
</template>

<script>
export default {
    props: {
        displayName: null,
    },
}
</script>
