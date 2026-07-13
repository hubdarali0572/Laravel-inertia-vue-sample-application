<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed, onMounted, onUnmounted, ref, watch } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import PublicSiteNavLink from "@/Components/PublicSite/PublicSiteNavLink.vue";
import { useDarkMode } from "@/composables/useDarkMode";

const page = usePage();
const { isDark, toggleDarkMode } = useDarkMode();

const canLogin = computed(() => page.props.canLogin);
const canRegister = computed(() => page.props.canRegister);

const isMobileMenuOpen = ref(false);

const navItems = [
    { label: "Home", href: route("publicSite.home"), routeName: "publicSite.home" },
    { label: "Features", href: `${route("publicSite.home")}#features`, routeName: null },
    { label: "About", href: route("publicSite.about"), routeName: "publicSite.about" },
    { label: "Tech Stack", href: `${route("publicSite.home")}#stack`, routeName: null },
    { label: "Security", href: `${route("publicSite.home")}#security`, routeName: null },
];

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

const onResize = () => {
    if (window.innerWidth >= 768 && isMobileMenuOpen.value) {
        closeMobileMenu();
    }
};

onMounted(() => {
    window.addEventListener("resize", onResize);
});

onUnmounted(() => {
    window.removeEventListener("resize", onResize);
    document.body.style.removeProperty("overflow");
});
</script>

<template>
    <header
        class="fixed inset-x-0 top-0 z-50 w-full max-w-full overflow-x-hidden border-b border-slate-200/80 bg-white/90 backdrop-blur-lg dark:border-slate-800/80 dark:bg-slate-950/90"
    >
        <nav
            class="mx-auto flex h-14 w-full max-w-7xl items-center justify-between gap-2 overflow-x-hidden px-3 sm:h-16 sm:gap-3 sm:px-6 lg:px-8"
            aria-label="Primary"
        >
            <!-- Brand: logo only on xs, name from sm -->
            <Link
                :href="route('publicSite.home')"
                class="flex min-w-0 items-center gap-2 sm:gap-2.5"
                @click="closeMobileMenu"
            >
                <ApplicationLogo
                    class="h-7 w-7 shrink-0 fill-indigo-600 sm:h-8 sm:w-8 dark:fill-indigo-400"
                />
                <span
                    class="hidden truncate text-sm font-bold tracking-tight text-slate-800 sm:inline dark:text-white"
                >
                    Unified Media
                </span>
            </Link>

            <!-- Desktop / tablet navigation -->
            <div class="hidden items-center gap-5 md:flex lg:gap-8">
                <PublicSiteNavLink
                    v-for="item in navItems"
                    :key="item.label"
                    :href="item.href"
                    :active="isActive(item)"
                >
                    {{ item.label }}
                </PublicSiteNavLink>
            </div>

            <!-- Actions: compact on mobile to prevent overflow -->
            <div class="flex shrink-0 items-center gap-1 sm:gap-2">
                <button
                    type="button"
                    @click="toggleDarkMode"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-100 sm:h-10 sm:w-10 dark:text-slate-400 dark:hover:bg-slate-800"
                    :aria-label="
                        isDark
                            ? 'Switch to light mode'
                            : 'Switch to dark mode'
                    "
                >
                    <svg
                        v-if="isDark"
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        aria-hidden="true"
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
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
                        />
                    </svg>
                </button>

                <!-- Auth CTAs: desktop/tablet only — mobile uses the drawer -->
                <template v-if="canLogin">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="hidden h-10 items-center rounded-lg bg-indigo-600 px-4 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-500 md:inline-flex"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="hidden h-10 items-center rounded-lg px-4 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-100 md:inline-flex dark:text-slate-200 dark:hover:bg-slate-800"
                        >
                            Sign In
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="hidden h-10 items-center rounded-lg bg-indigo-600 px-4 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-500 md:inline-flex"
                        >
                            Get Started
                        </Link>
                    </template>
                </template>

                <!-- Mobile menu toggle -->
                <button
                    type="button"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-600 transition-colors hover:bg-slate-100 sm:h-10 sm:w-10 md:hidden dark:text-slate-300 dark:hover:bg-slate-800"
                    :aria-expanded="isMobileMenuOpen"
                    aria-controls="public-site-mobile-menu"
                    :aria-label="isMobileMenuOpen ? 'Close menu' : 'Open menu'"
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
                            stroke-width="1.75"
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
                            stroke-width="1.75"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
        </nav>

        <!-- Mobile navigation panel -->
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 -translate-y-1"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-1"
        >
            <div
                v-show="isMobileMenuOpen"
                id="public-site-mobile-menu"
                class="border-t border-slate-200/80 bg-white md:hidden dark:border-slate-800/80 dark:bg-slate-950"
            >
                <div
                    class="mx-auto max-h-[calc(100dvh-3.5rem)] overflow-y-auto px-3 py-3 sm:max-h-[calc(100dvh-4rem)] sm:px-6"
                >
                    <div class="flex flex-col gap-1">
                        <PublicSiteNavLink
                            v-for="item in navItems"
                            :key="`mobile-${item.label}`"
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
                                class="inline-flex h-11 items-center justify-center rounded-lg bg-indigo-600 px-4 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-500"
                                @click="closeMobileMenu"
                            >
                                Dashboard
                            </Link>
                        </template>
                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="inline-flex h-11 items-center justify-center rounded-lg px-4 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800"
                                @click="closeMobileMenu"
                            >
                                Sign In
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="inline-flex h-11 items-center justify-center rounded-lg bg-indigo-600 px-4 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-500"
                                @click="closeMobileMenu"
                            >
                                Get Started
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </Transition>
    </header>

    <!-- Overlay under header -->
    <Transition
        enter-active-class="transition-opacity duration-200 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-150 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="isMobileMenuOpen"
            class="fixed inset-0 z-40 bg-slate-950/40 md:hidden"
            aria-hidden="true"
            @click="closeMobileMenu"
        />
    </Transition>
</template>
