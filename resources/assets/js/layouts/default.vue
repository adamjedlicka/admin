<template>
    <div class="h-screen">

        <HeaderBar />

        <div class="flex bg-grey-light">
            <SideBar id="sidebar" />

            <div class="flex-1 p-4 overflow-x-hidden">
                <router-view :key="$route.path" />
            </div>

        </div>

        <Toasts />
        <ConfirmModal />

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
