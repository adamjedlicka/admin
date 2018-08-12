<template>
    <div>

        <div v-if="!loaded"
            id="spinner"
            class="flex justify-center" >

            <div class="flex-shrink bg-white p-8 rounded-lg shadow-md text-center">
                <i class="text-xl fas fa-spinner"></i>
            </div>

        </div>

        <slot :style="style" />
    </div>
</template>

<script>
export default {
    data() {
        return {
            loaded: false,
        }
    },

    computed: {
        style() {
            return this.loaded ? null : { display: 'none' }
        }
    },

    async created() {
        let component = this.$slots.default[0]
        let asyncData = component.componentOptions.Ctor.options.asyncData

        let data = await asyncData({
            props: component.componentOptions.propsData
        })

        this.$nextTick(() => {
            for (let prop in data) {
                this.$slots.default[0].componentInstance[prop] = data[prop]
            }

            this.loaded = true
        })
    }
}
</script>

<style scoped>
#spinner {
  animation: hide 0.2s;
}

.fa-spinner {
  animation: spin 2s linear infinite;
}

@keyframes hide {
  0% {
    opacity: 0;
  }
  50% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
