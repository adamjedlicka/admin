<template>
    <tbody>

        <DialBodyRow v-for="(row, i) in rows" :key="i"
            class="hover:bg-grey-lighter"
            :fields="fields"
            :row="row"
            :links="links"
            @update="$emit('update')" >

        </DialBodyRow>

    </tbody>
</template>

<script>
import DialBodyRow from './DialBodyRow'

export default {
    props: {
        fields: Array,
        rows: Array,
        links: Object,
    },

    components: {
        DialBodyRow,
    }
}
</script>
