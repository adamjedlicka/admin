<template>
    <tbody>

        <DialBodyRow v-for="(resource, i) in resources" :key="i"
            class="hover:bg-grey-lighter"
            :resource="resource" />

    </tbody>
</template>

<script>
import DialBodyRow from './DialBodyRow'

export default {
    props: {
        resources: Array,
    },

    components: {
        DialBodyRow,
    }
}
</script>
