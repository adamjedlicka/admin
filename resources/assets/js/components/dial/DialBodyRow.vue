<template>
    <tr
        class="hover:bg-grey-lighter" >

        <td v-for="(field, j) in row.fields" :key="j"
            class="p-4 text-black text-left" >

            <div class="truncate">
                <component
                    :is="`${field.type}-index-field`"
                    :field="field"
                    :value="field.value" />
            </div>

        </td>

        <!-- CRUD buttons -->
        <td class="text-right pr-2 whitespace-no-wrap">

            <router-link v-if="row.policies.view" :to="detailUrl"
                title="Detail"
                class="text-grey hover:text-black cursor-pointer" >
                <i class="py-4 px-1 far fa-eye"></i>
            </router-link>

            <router-link v-if="row.policies.update" :to="editUrl"
                title="Edit"
                class="text-grey hover:text-black cursor-pointer" >
                <i class="py-4 px-1 far fa-edit"></i>
            </router-link>

            <a v-if="row.policies.delete" @click="onDelete"
                title="Detail"
                class="text-grey hover:text-red cursor-pointer" >
                <i class="py-4 px-1 far fa-trash-alt"></i>
            </a>

            <a v-if="links.detach" @click="onDetach"
                title="Detail"
                class="text-grey hover:text-red cursor-pointer" >
                <i class="py-4 px-1 fas fa-unlink"></i>
            </a>

        </td>

    </tr>
</template>

<script>
export default {
    props: [
        'row',
        'links',
    ],

    computed: {
        detailUrl() {
            return `/resources/${this.row.name}/${this.row.key}`
        },

        editUrl() {
            return `/resources/${this.row.name}/${this.row.key}/edit`
        },

        deleteUrl() {
            return `/api/resources/${this.row.name}/${this.row.key}`
        }
    },

    methods: {
        async onDelete() {
            let ok = await modalConfirm('Delete', `Delete ${this.row.title} ?`, true)
            if (!ok) return

            let response = await this.$delete(this.deleteUrl)
            if (response.status == 'success') this.$emit('update')

        },
    }
}
</script>
