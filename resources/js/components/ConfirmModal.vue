<template>
    <Modal ref="modal" :title="title" :body="body">
        <template slot="footer">
            <div class="flex justify-end">
                <div class="buttons">
                    <a class="btn btn-blue" @click="onCancel">
                        Cancel
                    </a>

                    <a class="btn" :class="danger ? 'btn-red' : 'btn-green'" @click="onConfirm">
                        Confirm
                    </a>
                </div>
            </div>
        </template>
    </Modal>
</template>

<script>
export default {
    data() {
        return {
            resolve: null,
            danger: null,

            title: '',
            body: '',
        }
    },

    mounted() {
        window.modalConfirm = (title, body, danger = false) => {
            this.title = title
            this.body = body
            this.danger = danger

            this.$refs.modal.show()

            return new Promise(resolve => this.resolve = resolve)
        }
    },

    methods: {
        onConfirm() {
            this.$refs.modal.hide()
            this.resolve(true)
        },

        onCancel() {
            this.$refs.modal.hide()
            this.resolve(false)
        },
    }
}
</script>
