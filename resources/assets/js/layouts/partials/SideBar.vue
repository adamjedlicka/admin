<template>
    <div class="w-64 bg-grey-darker text-white">
        <nav>

            <router-link
                class="p-4 block hover:bg-grey-dark cursor-pointer font-bold text-xl text-white no-underline"
                to="/" >
                Home
            </router-link>

            <router-link
                class="p-4 block hover:bg-grey-dark cursor-pointer font-bold text-xl text-white no-underline"
                to="/resources" >
                Resources
            </router-link>

            <router-link
                v-for="(resource, i) in resources" :key="i"
                class="pl-8 p-2 block hover:bg-grey-dark cursor-pointer text-white no-underline"
                :to="`/resources/${resource.name}`" >
                {{ resource.pluralName }}
            </router-link>

        </nav>
    </div>
</template>

<script>
export default {
    data() {
        return {
            resources: [],
        }
    },

    async mounted() {
        this.resources = await this.$get('/api/resources')
    }
}
</script>
