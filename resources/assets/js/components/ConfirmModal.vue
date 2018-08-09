<template>
    <div v-if="visible">
        <div class="fixed pin bg-black opacity-75 z-50">
        </div>

        <div class="fixed pin z-50">
            <div class="flex justify-center items-center h-screen">
                <div class="container bg-white rounded-lg shadow-lg max-w-sm">

                    <div class="p-4 text-xl text-grey-darker font-bold flex justify-between border-b">
                        <span>
                            {{ title }}
                        </span>
                        <span class="cursor-pointer"
                            @click="onCancel" >
                            <i class="fas fa-times"></i>
                        </span>
                    </div>

                    <div class="p-4 text-grey-darkest">
                        {{ body }}
                    </div>

                    <div class="p-2 flex justify-end border-t">
                        <div class="btn btn-blue"
                            @click="onCancel" >
                            Cancel
                        </div>

                        <div class="btn ml-2"
                            :class="{'btn-green' : !danger, 'btn-red' : danger}"
                            @click="onOk" >
                            Ok
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            visible: false,
            resolve: null,
            danger: null,

            title: '',
            body: '',
        }
    },

    mounted() {
        window.modalConfirm = (title, body, danger = false) => {
            this.visible = true
            this.title = title
            this.body = body
            this.danger = danger

            return new Promise(resolve => this.resolve = resolve)
        }
    },

    methods: {
        onOk() {
            this.visible = false
            this.resolve(true)
        },

        onCancel() {
            this.visible = false
            this.resolve(false)
        },
    }
}
</script>
