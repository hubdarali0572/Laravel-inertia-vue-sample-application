<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import PublicSiteNavLink from "@/Components/PublicSite/PublicSiteNavLink.vue";
import { useDarkMode } from "@/composables/useDarkMode";

const page = usePage();
const { isDark, toggleDarkMode } = useDarkMode();

const canLogin = computed(() => page.props.canLogin);
const canRegister = computed(() => page.props.canRegister);

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
</script>

<template>
    <header
        class="fixed inset-x-0 top-0 z-50 border-b border-slate-200/80 bg-white/80 backdrop-blur-lg dark:border-slate-800/80 dark:bg-slate-950/80"
    >
        <nav
            class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8"
        >
            <Link :href="route('publicSite.home')" class="flex items-center gap-3">
                <ApplicationLogo
                    class="h-8 w-auto fill-indigo-600 dark:fill-indigo-400"
                />
                <span
                    class="hidden sm:block text-sm font-bold tracking-tight text-slate-800 dark:text-white"
                >
                    Unified Media
                </span>
            </Link>

            <div class="hidden md:flex items-center gap-8">
                <PublicSiteNavLink
                    v-for="item in navItems"
                    :key="item.label"
                    :href="item.href"
                    :active="isActive(item)"
                >
                    {{ item.label }}
                </PublicSiteNavLink>
            </div>

            <div class="flex items-center gap-3">
                <button
                    type="button"
                    @click="toggleDarkMode"
                    class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors dark:text-slate-400 dark:hover:bg-slate-800"
                    :aria-label="
                        isDark
                            ? 'Switch to light mode'
                            : 'Switch to dark mode'
                    "
                >
                    <svg
                        v-if="isDark"
                        class="w-5 h-5"
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
                        class="w-5 h-5"
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

                <template v-if="canLogin">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="hidden sm:inline-flex rounded-lg px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition-colors dark:text-slate-200 dark:hover:bg-slate-800"
                        >
                            Sign In
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors"
                        >
                            Get Started
                        </Link>
                    </template>
                </template>
            </div>
        </nav>
    </header>
</template>
