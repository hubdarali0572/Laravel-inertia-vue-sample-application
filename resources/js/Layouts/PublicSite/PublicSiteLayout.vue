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
            disable: false,
            // Avoid horizontal transforms that cause mobile x-scroll
            mirror: false,
        });
    });
});
</script>

<template>
    <div
        class="theme-app-bg flex min-h-screen w-full max-w-full flex-col overflow-x-hidden"
    >
        <PublicSiteHeader />

        <main class="w-full max-w-full flex-1 overflow-x-hidden pt-14 sm:pt-16">
            <slot />
        </main>

        <PublicSiteFooter />
    </div>
</template>
