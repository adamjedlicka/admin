export default {
    props: {
        rules: Array,
    },

    data() {
        return {
            valid: true,
            errors: [],
        }
    },

    watch: {
        value(value) {
            if (this.rules.indexOf('required') > -1) {
                if (!value) {
                    this.valid = false
                    this.errors.push('This field is required')
                } else {
                    this.valid = true
                    this.errors = []
                }
            }
        }
    }
}
