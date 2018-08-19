<template>
    <div class="p-4">

        <div class="flex justify-between pb-4">
            <div>
                <h2 class="h2">{{ field.displayName }}</h2>
            </div>

            <div>
                <a @click="attach" class="btn btn-blue">
                    Attach
                </a>
            </div>
        </div>

        <div class="panel">

            <Dial
                :source="source"
                :prefix="field.name" />

        </div>

        <AttachModal ref="attachModal" />

    </div>
</template>

<script>
export default {
    props: {
        field: Object,
    },

    computed: {
        source() {
            let resource = this.$route.params.resource
            let key = this.$route.params.key
            let ofWhat = this.field.name

            return `/api/resources/${resource}/${key}/belongsToMany/${ofWhat}`
        },
    },

    methods: {
        attach() {
            this.$refs.attachModal.show()
        }
    }
}
</script>
