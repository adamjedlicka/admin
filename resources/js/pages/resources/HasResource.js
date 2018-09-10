export default {
    data() {
        return {
            resource: null,
            model: {},
            errors: {},
        }
    },

    mounted() {
        this.fetchData()
    },

    methods: {
        async fetchData() {
            this.resource = await this.$get(this.source)

            this.resource.fields.forEach(field => {
                this.model[field.name] = this.valueOf(field)
            })
        },

        valueOf(field) {
            return field.value
        },

        onInput(field, value) {
            this.$set(this.model, field, value)
            this.$forceUpdate()
        }
    }
}
