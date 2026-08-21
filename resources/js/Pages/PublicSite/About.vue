<script setup>
import { Head } from "@inertiajs/vue3";
import PublicSiteLayout from "@/Layouts/PublicSite/PublicSiteLayout.vue";
import { computed } from "vue";
import { useI18n } from "@/composables/useI18n";

const { t } = useI18n();
const canonicalUrl = computed(() => route("publicSite.about"));
const seoTitle = computed(() => t("public.about_seo"));
const seoDescription = computed(() => t("public.about_description"));
const socialTitle = computed(() => `${t("public.about_seo")} - ${t("app.brand")}`);
</script>

<template>
    <PublicSiteLayout>
        <Head :title="seoTitle">
            <meta
                head-key="description"
                name="description"
                :content="seoDescription"
            />
            <meta
                head-key="robots"
                name="robots"
                content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1"
            />
            <link head-key="canonical" rel="canonical" :href="canonicalUrl" />
            <meta head-key="og:type" property="og:type" content="website" />
            <meta
                head-key="og:title"
                property="og:title"
                :content="socialTitle"
            />
            <meta
                head-key="og:description"
                property="og:description"
                :content="seoDescription"
            />
            <meta
                head-key="og:url"
                property="og:url"
                :content="canonicalUrl"
            />
            <meta
                head-key="twitter:card"
                name="twitter:card"
                content="summary_large_image"
            />
            <meta
                head-key="twitter:title"
                name="twitter:title"
                :content="socialTitle"
            />
            <meta
                head-key="twitter:description"
                name="twitter:description"
                :content="seoDescription"
            />
        </Head>

        <section class="py-20 lg:py-28">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-3xl" data-aos="fade-up">
                    <p
                        class="theme-accent-text text-xs font-bold uppercase tracking-[0.2em]"
                    >
                        {{ t("public.about_kicker") }}
                    </p>
                    <h1
                        class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl md:text-5xl"
                    >
                        {{ t("public.about_title") }}
                    </h1>
                    <p
                        class="mt-6 text-lg leading-8 text-slate-600 dark:text-slate-300"
                    >
                        {{ t("public.about_body") }}
                    </p>
                </div>

                <div
                    class="mx-auto mt-16 grid max-w-5xl grid-cols-1 gap-8 md:grid-cols-2"
                >
                    <article
                        data-aos="fade-up"
                        class="rounded-2xl bg-white p-8 ring-1 ring-slate-200 shadow-sm dark:bg-slate-900/60 dark:ring-slate-800"
                    >
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">
                            {{ t("public.about_mission") }}
                        </h2>
                        <p
                            class="mt-3 text-sm leading-relaxed text-slate-600 dark:text-slate-400"
                        >
                            {{ t("public.about_mission_body") }}
                        </p>
                    </article>

                    <article
                        data-aos="fade-up"
                        data-aos-delay="100"
                        class="rounded-2xl bg-white p-8 ring-1 ring-slate-200 shadow-sm dark:bg-slate-900/60 dark:ring-slate-800"
                    >
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">
                            {{ t("public.about_provide") }}
                        </h2>
                        <p
                            class="mt-3 text-sm leading-relaxed text-slate-600 dark:text-slate-400"
                        >
                            {{ t("public.about_provide_body") }}
                        </p>
                    </article>
                </div>
            </div>
        </section>
    </PublicSiteLayout>
</template>
