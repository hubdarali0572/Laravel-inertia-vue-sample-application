<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed, onMounted, onUnmounted, ref, watch } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import PublicSiteNavLink from "@/Components/PublicSite/PublicSiteNavLink.vue";
import { useI18n } from "@/composables/useI18n";

const page = usePage();
const { t } = useI18n();

const canLogin = computed(() => page.props.canLogin);
const canRegister = computed(() => page.props.canRegister);

const isMobileMenuOpen = ref(false);
/** true from 1024px up — drives menu vs hamburger (not CSS-only) */
const isDesktop = ref(false);

const navItems = computed(() => [
    { label: t("public.home"), href: route("publicSite.home"), routeName: "publicSite.home" },
    { label: t("public.features"), href: `${route("publicSite.home")}#features`, routeName: null },
    { label: t("public.about"), href: route("publicSite.about"), routeName: "publicSite.about" },
    { label: t("public.stack"), href: `${route("publicSite.home")}#stack`, routeName: null },
    { label: t("public.security"), href: `${route("publicSite.home")}#security`, routeName: null },
]);

const isActive = (item) => {
    if (!item.routeName) {
        return false;
    }

    return route().current(item.routeName);
};

const closeMobileMenu = () => {
    isMobileMenuOpen.value = false;
};

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

let mediaQuery = null;

const syncViewport = () => {
    isDesktop.value = mediaQuery ? mediaQuery.matches : false;
    if (isDesktop.value) {
        closeMobileMenu();
    }
};

watch(
    () => page.url,
    () => {
        closeMobileMenu();
    },
);

watch(isMobileMenuOpen, (open) => {
    if (open) {
        document.body.style.overflow = "hidden";
    } else {
        document.body.style.removeProperty("overflow");
    }
});

onMounted(() => {
    mediaQuery = window.matchMedia("(min-width: 1024px)");
    syncViewport();
    mediaQuery.addEventListener("change", syncViewport);
});

onUnmounted(() => {
    if (mediaQuery) {
        mediaQuery.removeEventListener("change", syncViewport);
    }
    document.body.style.removeProperty("overflow");
});
</script>

<template>
    <header
                    class="theme-public-header fixed inset-x-0 top-0 z-50 w-full max-w-[100vw]"
    >
        <nav
            class="mx-auto flex h-14 w-full max-w-7xl items-center gap-2 px-3 sm:h-16 sm:px-6 lg:px-8"
            aria-label="Primary"
        >
            <!-- Brand -->
            <Link
                :href="route('publicSite.home')"
                class="flex min-w-0 flex-1 items-center gap-2 overflow-hidden"
                :aria-label="t('app.brand')"
                @click="closeMobileMenu"
            >
                <ApplicationLogo
                    class="theme-icon-brand h-7 w-7 shrink-0 sm:h-8 sm:w-8"
                    aria-hidden="true"
                />
                <span
                    class="truncate text-sm font-bold tracking-tight text-slate-800 dark:text-white"
                >
                    {{ t("app.brand") }}
                </span>
            </Link>

            <!-- Desktop nav links -->
            <div
                v-if="isDesktop"
                class="flex shrink-0 items-center gap-6 xl:gap-8"
            >
                <PublicSiteNavLink
                    v-for="item in navItems"
                    :key="item.label"
                    :href="item.href"
                    :active="isActive(item)"
                >
                    {{ item.label }}
                </PublicSiteNavLink>
            </div>

            <!-- Right actions: never overflow -->
            <div class="ml-auto flex shrink-0 items-center gap-1">
                <!-- Desktop auth only (not in DOM on mobile → no overflow) -->
                <template v-if="isDesktop && canLogin">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="theme-btn-primary hidden lg:inline-flex"
                    >
                        {{ t("public.dashboard") }}
                    </Link>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="theme-btn-ghost hidden lg:inline-flex h-10"
                        >
                            {{ t("public.login") }}
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="theme-btn-primary hidden lg:inline-flex"
                        >
                            {{ t("public.register") }}
                        </Link>
                    </template>
                </template>

                <!-- Mobile / tablet hamburger (right side) -->
                <button
                    v-if="!isDesktop"
                    type="button"
                    class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800"
                    :aria-expanded="isMobileMenuOpen"
                    aria-controls="public-site-mobile-menu"
                    :aria-label="
                        isMobileMenuOpen
                            ? t('header.close_menu')
                            : t('header.open_menu')
                    "
                    @click="toggleMobileMenu"
                >
                    <svg
                        v-if="!isMobileMenuOpen"
                        class="h-6 w-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                    </svg>
                    <svg
                        v-else
                        class="h-6 w-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
        </nav>

        <!-- Mobile dropdown -->
        <div
            v-if="!isDesktop && isMobileMenuOpen"
            id="public-site-mobile-menu"
            class="theme-surface border-t"
        >
            <div
                class="mx-auto max-h-[calc(100dvh-3.5rem)] max-w-7xl overflow-y-auto px-3 py-3 sm:px-6"
            >
                <div class="flex flex-col gap-1">
                    <PublicSiteNavLink
                        v-for="item in navItems"
                        :key="`m-${item.label}`"
                        :href="item.href"
                        :active="isActive(item)"
                        mobile
                        @navigate="closeMobileMenu"
                    >
                        {{ item.label }}
                    </PublicSiteNavLink>
                </div>

                <div
                    v-if="canLogin"
                    class="mt-3 flex flex-col gap-2 border-t border-slate-200 pt-3 dark:border-slate-800"
                >
                    <template v-if="$page.props.auth.user">
                        <Link
                            :href="route('dashboard')"
                            class="theme-btn-primary inline-flex min-h-11 items-center justify-center"
                            @click="closeMobileMenu"
                        >
                            {{ t("public.dashboard") }}
                        </Link>
                    </template>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="theme-btn-secondary inline-flex min-h-11 items-center justify-center"
                            @click="closeMobileMenu"
                        >
                            {{ t("public.login") }}
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="theme-btn-primary inline-flex min-h-11 items-center justify-center"
                            @click="closeMobileMenu"
                        >
                            {{ t("public.register") }}
                        </Link>
                    </template>
                </div>
            </div>
        </div>
    </header>

    <div
        v-if="!isDesktop && isMobileMenuOpen"
        class="fixed inset-0 z-40 bg-black/40"
        aria-hidden="true"
        @click="closeMobileMenu"
    />
</template>
