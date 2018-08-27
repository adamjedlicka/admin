<template>
    <div class="h-screen">

        <div class="flex h-screen bg-grey-light">
            <SideBar id="sidebar" />

            <div class="flex-1 overflow-x-hidden">
                <HeaderBar />

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
