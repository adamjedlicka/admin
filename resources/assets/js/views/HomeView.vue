<template>
    <div class="h-screen">

        <HeaderBar />

        <div class="flex bg-grey-lighter">
            <SideBar id="sidebar" />

            <div class="flex-1 p-4">
                <router-view />
            </div>

        </div>

    </div>
</template>

<script>
import HeaderBar from "./partials/HeaderBar"
import SideBar from "./partials/SideBar"

export default {
    components: {
        HeaderBar,
        SideBar
    }
}
</script>

<style lang="scss">
#sidebar {
  height: calc(100vh - 4rem);
}
</style>
