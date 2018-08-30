<template>
    <tbody>

        <DialBodyRow v-for="(row, i) in rows" :key="i"
            class="hover:bg-grey-lighter"
            :row="row"
            :links="links" >

            <template slot="buttons" slot-scope="scope">
                <slot name="buttons" :resource="scope.resource" />
            </template>

        </DialBodyRow>

    </tbody>
</template>

<script>
import DialBodyRow from './DialBodyRow'

export default {
    props: [
        'rows',
        'links',
    ],

    components: {
        DialBodyRow,
    }
}
</script>
