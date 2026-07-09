<script setup>
import { onMounted, nextTick } from "vue";
import AOS from "aos";
import PublicSiteHeader from "@/Components/PublicSite/PublicSiteHeader.vue";
import PublicSiteFooter from "@/Components/PublicSite/PublicSiteFooter.vue";

onMounted(() => {
    nextTick(() => {
        AOS.init({
            duration: 700,
            once: true,
            offset: 60,
        });
    });
});
</script>

<template>
    <div
        class="theme-app-bg min-h-screen"
    >
        <PublicSiteHeader />

        <main class="pt-[73px]">
            <slot />
        </main>

        <PublicSiteFooter />
    </div>
</template>
