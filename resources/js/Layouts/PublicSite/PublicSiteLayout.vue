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
        class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100"
    >
        <PublicSiteHeader />

        <main class="pt-[73px]">
            <slot />
        </main>

        <PublicSiteFooter />
    </div>
</template>
