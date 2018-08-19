<template>
    <tbody>

        <DialBodyRow v-for="(resource, i) in resources" :key="i"
            class="hover:bg-grey-lighter"
            :resource="resource"
            :query="query"
            @update="$emit('update')" />

    </tbody>
</template>

<script>
import DialBodyRow from './DialBodyRow'

export default {
    props: {
        resources: Array,
        query: Object,
    },

    components: {
        DialBodyRow,
    }
}
</script>
