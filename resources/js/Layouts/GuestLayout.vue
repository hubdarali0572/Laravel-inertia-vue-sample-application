<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import LanguageSwitcher from "@/Components/LanguageSwitcher.vue";
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";
import { useDarkMode } from "@/composables/useDarkMode";
import { useI18n } from "@/composables/useI18n";

const { isDark, toggleDarkMode } = useDarkMode();
const { t } = useI18n();

const highlights = computed(() => [
    t("auth.highlight_rbac"),
    t("auth.highlight_media"),
    t("auth.highlight_logs"),
]);
</script>

<template>
    <div
        class="theme-app-bg flex min-h-screen w-full max-w-[100vw] flex-col overflow-x-hidden lg:flex-row"
    >
        <!-- Branding panel (desktop only) -->
        <div class="theme-guest-brand hidden w-full lg:flex lg:w-[45%] xl:w-1/2">
            <div
                class="relative z-10 flex w-full flex-col justify-between p-10 xl:p-16"
            >
                <div class="flex items-center justify-between gap-4">
                    <Link href="/" class="flex w-fit items-center gap-3">
                        <ApplicationLogo class="theme-icon-brand h-9 w-9 shrink-0" />
                        <span class="text-sm font-bold tracking-tight">{{
                            t("app.brand")
                        }}</span>
                    </Link>
                    <LanguageSwitcher />
                </div>

                <div class="max-w-md">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[var(--color-accent)]">
                        {{ t("app.platform") }}
                    </p>
                    <h1
                        class="mt-3 text-3xl font-extrabold leading-tight tracking-tight xl:text-4xl"
                    >
                        {{ t("auth.guest_heading") }}
                        <span class="text-[var(--color-accent)]">{{
                            t("auth.guest_heading_accent")
                        }}</span>
                        {{ t("auth.guest_for") }}
                    </h1>
                    <p class="mt-4 text-sm leading-relaxed text-white/70">
                        {{ t("auth.guest_text") }}
                    </p>

                    <ul class="mt-8 space-y-3">
                        <li
                            v-for="item in highlights"
                            :key="item"
                            class="flex items-center gap-3 text-sm text-white/80"
                        >
                            <span
                                class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full bg-[var(--color-accent)]/20"
                            >
                                <svg
                                    class="h-3 w-3 text-[var(--color-accent)]"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="3"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            </span>
                            {{ item }}
                        </li>
                    </ul>
                </div>

                <p class="text-xs text-white/40">
                    &copy; {{ new Date().getFullYear() }}
                    {{ t("app.brand") }} {{ t("app.platform") }}
                </p>
            </div>
        </div>

        <!-- Form panel -->
        <div
            class="flex min-h-screen w-full min-w-0 flex-1 flex-col overflow-x-hidden"
        >
            <header
                class="flex shrink-0 items-center justify-between gap-2 px-4 py-4 sm:px-6 sm:py-5 lg:px-10"
            >
                <Link
                    href="/"
                    class="flex min-w-0 items-center gap-2 lg:invisible lg:pointer-events-none"
                    :aria-label="t('app.brand')"
                >
                    <ApplicationLogo
                        class="theme-icon-brand h-7 w-7 shrink-0 sm:h-8 sm:w-8"
                    />
                    <span
                        class="theme-heading truncate text-sm font-bold"
                        >{{ t("app.brand") }}</span
                    >
                </Link>

                <div class="flex shrink-0 items-center gap-1 sm:gap-2">
                    <LanguageSwitcher />
                    <Link
                        href="/"
                        class="theme-form-back-link hidden sm:inline-flex"
                    >
                        &larr; {{ t("public.back_home") }}
                    </Link>
                    <Link
                        href="/"
                        class="theme-header-control theme-header-control-icon sm:hidden"
                        :aria-label="t('public.back_home')"
                    >
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.75"
                                d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"
                            />
                        </svg>
                    </Link>
                    <button
                        type="button"
                        class="theme-header-control theme-header-control-icon"
                        :aria-label="
                            isDark
                                ? t('header.light_mode')
                                : t('header.dark_mode')
                        "
                        @click="toggleDarkMode"
                    >
                        <svg
                            v-if="isDark"
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
                            />
                        </svg>
                        <svg
                            v-else
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
                            />
                        </svg>
                    </button>
                </div>
            </header>

            <main
                class="flex flex-1 items-start justify-center px-4 pb-8 pt-2 sm:items-center sm:px-6 sm:pb-10 lg:px-10"
            >
                <div class="w-full max-w-md min-w-0">
                    <div class="theme-guest-card">
                        <slot />
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
