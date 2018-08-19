<template>
    <Modal ref="modal" :title="title" size="large">
        <template slot="body">
            LOL
        </template>
    </Modal>
</template>

<script>
export default {
    computed: {
        title() {
            return 'Hello, World!'
        }
    },

    methods: {
        show() {
            this.$refs.modal.show()
        }
    }
}
</script>
