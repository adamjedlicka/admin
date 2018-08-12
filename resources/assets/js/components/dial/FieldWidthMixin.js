export default {
    methods: {
        fieldWidth(field) {
            switch (field.indexSize) {
                case 'oneHalf':
                    return 'width: 50%'

                case 'small':
                    return 'min-width: 5rem; max-width: 5rem'

                case 'normal':
                    return 'min-width: 20rem; max-width: 20rem'
            }
        }
    }
}
