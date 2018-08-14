<template>
    <div>

        <select @input="onInput" :value="selected"
            class="bg-white border border-grey rounded-lg py-2 px-4 outline-none focus:shadow-outline" >

            <option value=""></option>

            <option v-for="(resource, i) in meta.data.resources" :key="i"
                :value="i" >
                {{ resource.title }}
            </option>

        </select>

    </div>
</template>

<script>
export default {
    props: {
        field: Object,
    },

    data() {
        return {
            selected: null,
        }
    },

    mounted() {
        for (let i in this.meta.data.resources) {
            let resource = this.meta.data.resources[i]

            if (resource.key == this.field.key) {
                this.selected = i
            }
        }
    },

    methods: {
        onInput(e, a) {
            this.selected = e.target.value
            this.$emit('input', this.meta.data.resources[this.selected])
        }
    }
}
</script>
