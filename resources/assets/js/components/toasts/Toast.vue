<template>
    <div class="panel p-4 max-w-xs md:max-w-sm"
        :class="[bgColor]"
        @mouseover.stop="onMouseOver"
        @mouseout.stop="onMouseOut" >

        <div class="flex justify-between">
            <div class="text-xl font-bold pb-4">
                {{ title || status }}
            </div>

            <span class="cursor-pointer"
                @click="remove" >
                <i class="fas fa-times"></i>
            </span>
        </div>

        <div class="">
            {{ message }}
        </div>

    </div>
</template>

<script>
export default {
    props: {
        message: String,
        title: String,
        status: String,
    },

    data() {
        return {
            timeout: null
        }
    },

    computed: {
        bgColor() {
            switch (this.status) {
                case 'Info':
                    return 'bg-blue-light'
                case 'Success':
                    return 'bg-green-light'
                case 'Error':
                    return 'bg-red-light'
            }
        }
    },

    mounted() {
        this.timeout = setTimeout(this.remove, 5000)
    },

    methods: {
        remove() {
            clearTimeout(this.timeout)
            this.$emit('remove')
        },

        onMouseOver(e) {
            clearTimeout(this.timeout)
        },

        onMouseOut() {
            this.timeout = setTimeout(this.remove, 3000)
        }
    }
}
</script>

<style scoped>
.panel {
  min-width: 15rem;
}
</style>
