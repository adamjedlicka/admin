<template>
    <div class="flex w-screen h-screen bg-grey-light justify-center">

        <Panel title="Error..."
            class="w-128 self-center" >

            <template slot="buttons">
                <a class="btn btn-blue"
                    @click="$router.go(-1)" >
                    Go back
                </a>
            </template>

            <template slot="body">
                <div class="p-4">
                    <h1 class="h1 pb-4">{{ code }}</h1>

                    <router-view />
                </div>
            </template>


        </Panel>

    </div>
</template>

<script>
export default {
    computed: {
        code() {
            return this.$route.path.substring(1)
        }
    }
}
</script>
