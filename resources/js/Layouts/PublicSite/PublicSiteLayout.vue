<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import { onMounted, nextTick, computed } from "vue";
import AOS from "aos";
import PublicSiteHeader from "@/Components/PublicSite/PublicSiteHeader.vue";
import PublicSiteFooter from "@/Components/PublicSite/PublicSiteFooter.vue";

const cms = computed(() => usePage().props.institution || {});
const favicon = computed(() => cms.value.favicon_url || "/images/logo.jpeg");

onMounted(() => {
    document.documentElement.classList.remove("dark");
    document.documentElement.lang = "en";
    document.documentElement.dir = "ltr";

    nextTick(() => {
        AOS.init({
            duration: 550,
            once: true,
            offset: 48,
            easing: "ease-out",
        });
    });
});
</script>

<template>
    <div class="ps-site">
        <Head>
            <link
                head-key="icon"
                rel="icon"
                type="image/jpeg"
                :href="favicon"
            />
            <link
                head-key="apple-touch-icon"
                rel="apple-touch-icon"
                :href="favicon"
            />
        </Head>
        <a href="#main-content" class="ps-skip">Skip to main content</a>
        <PublicSiteHeader />
        <main id="main-content">
            <slot />
        </main>
        <PublicSiteFooter />
    </div>
</template>
