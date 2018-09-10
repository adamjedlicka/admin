<template>
    <div class="fixed pin-b pin-r mb-8 mr-8">

        <Toast v-for="(toast, i) in toasts" :key="i"
            class="toast"
            :message="toast.message"
            :title="toast.title"
            :status="toast.status"
            @remove="onRemove(i)" />

    </div>
</template>

<script>
import Toast from './Toast'

class Builder {

    constructor(message, title) {
        this.message = message
        this.title = title
        this.status = 'Info'
    }

    info() {
        this.status = 'Info'
        return this
    }

    success() {
        this.status = 'Success'
        return this
    }

    error() {
        this.status = 'Error'
        return this
    }

}

export default {
    data() {
        return {
            toasts: [],
        }
    },

    mounted() {
        window.toast = (message, title = null) => {
            let builder = new Builder(message, title)

            this.toasts.push(builder)

            return builder
        }
    },

    methods: {
        onRemove(i) {
            this.toasts.splice(i, 1)
        }
    },

    components: {
        Toast,
    }
}
</script>

<style scoped>
.toast:not(:first-child) {
  margin-top: 1rem;
}
</style>
