<template>
    <div v-if="visible">
        <div class="fixed pin bg-black opacity-75 z-50">
        </div>

        <div class="fixed pin z-50">
            <div class="flex justify-center items-center h-screen">
                <div class="container bg-white rounded-lg shadow-lg"
                    :class="[maxWidth]">

                    <div class="p-4 text-xl text-grey-darker font-bold flex justify-between border-b">
                        <span slot="header">
                            {{ title }}
                        </span>

                        <span class="cursor-pointer"
                            @click="hide" >
                            <i class="fas fa-times"></i>
                        </span>
                    </div>

                    <div class="p-4 text-grey-darkest">
                        <p slot="body">
                            {{ body }}
                        </p>
                    </div>

                    <div v-if="$slots.footer" class="p-2 border-t">
                        <slot name="footer" />
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: String,
        body: String,
        size: String,
    },

    data() {
        return {
            visible: false,
        }
    },

    computed: {
        maxWidth() {
            switch (this.size) {
                case 'large':
                    return ''
                default:
                    return 'max-w-sm'
            }
        }
    },

    methods: {
        show() {
            this.visible = true
        },

        hide() {
            this.visible = false
        },

        toggle() {
            this.visible = !this.visible
        }
    }
}
</script>
