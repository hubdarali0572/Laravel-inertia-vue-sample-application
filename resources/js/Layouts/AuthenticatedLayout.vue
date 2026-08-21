<script setup>
import { computed, onMounted, onUnmounted, ref, watch } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { useDarkMode } from "@/composables/useDarkMode";
import { usePermissions } from "@/composables/usePermissions";

const { isDark, toggleDarkMode } = useDarkMode();
const { can, roleName, isSuperAdmin } = usePermissions();
const page = usePage();

const isSidebarOpen = ref(false);
const isProfileMenuOpen = ref(false);
const profileMenuRef = ref(null);

const closeProfileMenuOnOutsideClick = (event) => {
    if (!isProfileMenuOpen.value) {
        return;
    }

    if (profileMenuRef.value?.contains(event.target)) {
        return;
    }

    isProfileMenuOpen.value = false;
};

onMounted(() => {
    document.addEventListener("mousedown", closeProfileMenuOnOutsideClick);
});

onUnmounted(() => {
    document.removeEventListener("mousedown", closeProfileMenuOnOutsideClick);
});

watch(
    () => page.url,
    () => {
        isSidebarOpen.value = false;
        isProfileMenuOpen.value = false;
    },
);

const userInitials = () => {
    const name = page.props.auth.user.name;
    return name
        .split(" ")
        .map((part) => part[0])
        .join("")
        .slice(0, 2)
        .toUpperCase();
};

const isProfileActive = () => route().current("profile.edit");

const allNavItems = computed(() => [
    {
        name: "Dashboard",
        icon: "M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6",
        route: "dashboard",
        active: route().current("dashboard"),
        show: true,
    },
    { category: "MAIN MODULES" },
    {
        name: "User Management",
        icon: "M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z",
        route: "users.index",
        active: route().current("users.*"),
        show: can("view user"),
    },
    {
        name: "Roles & Permissions",
        icon: "M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z",
        route: "roles.index",
        active: route().current("roles.*"),
        show: can("view role"),
    },
    {
        name: "Activity Logs",
        icon: "M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01",
        route: "activity.index",
        active: route().current("activity.*"),
        show: isSuperAdmin.value,
    },
]);

const navItems = computed(() => {
    const visible = allNavItems.value.filter(
        (item) => item.category || item.show,
    );

    return visible.filter((item, index, items) => {
        if (!item.category) {
            return true;
        }

        const next = items[index + 1];
        return next && !next.category;
    });
});

const pageTitle = computed(() => {
    if (route().current("dashboard")) return "Dashboard";
    if (route().current("users.*")) return "User Management";
    if (route().current("roles.*")) return "Roles & Permissions";
    if (route().current("activity.*")) return "Activity Logs";
    if (route().current("profile.*")) return "Profile";
    return "Dashboard";
});
</script>

<template>
    <div
        class="theme-app-bg flex h-screen overflow-hidden font-sans transition-colors"
    >
        <!-- MOBILE OVERLAY -->
        <transition
            enter-active-class="transition-opacity ease-linear duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity ease-linear duration-300"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isSidebarOpen"
                class="fixed inset-0 z-40 bg-black/60 lg:hidden"
                @click="isSidebarOpen = false"
            ></div>
        </transition>

        <!-- SIDEBAR -->
        <aside
            class="theme-sidebar fixed inset-y-0 left-0 z-50 flex w-72 flex-col transition-transform duration-300 ease-in-out lg:static lg:inset-0 lg:w-64 lg:translate-x-0"
            :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div
                class="flex shrink-0 items-center justify-between border-b border-slate-200 px-4 py-4 dark:border-slate-700"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="rounded-lg bg-indigo-600 p-2 shadow-lg shadow-indigo-600/30"
                    >
                        <svg
                            class="h-5 w-5 text-white"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                        >
                            <path
                                d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"
                            ></path>
                        </svg>
                    </div>
                    <span
                        class="text-sm font-bold tracking-wide text-slate-800 lg:text-base dark:text-white"
                        >Sample Project</span
                    >
                </div>
                <button
                    type="button"
                    class="p-2 text-slate-700 lg:hidden dark:text-white"
                    @click="isSidebarOpen = false"
                >
                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                    >
                        <path d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="custom-scrollbar flex-1 overflow-y-auto py-3">
                <template v-for="item in navItems" :key="item.name || item.category">
                    <div
                        v-if="item.category"
                        class="theme-sidebar-category px-5 pb-1.5 pt-3 text-[10px] font-semibold uppercase tracking-[0.15em]"
                    >
                        {{ item.category }}
                    </div>

                    <div v-else class="px-2.5">
                        <Link
                            :href="route(item.route)"
                            class="group mb-0.5 flex items-center rounded-lg px-3 py-2 transition-all duration-200 ease-in-out"
                            :class="
                                item.active
                                    ? 'theme-sidebar-nav-active'
                                    : 'theme-sidebar-nav-inactive'
                            "
                        >
                            <svg
                                class="mr-2.5 h-4 w-4 shrink-0 transition-colors duration-200"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                            >
                                <path
                                    :d="item.icon"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                            <span class="text-sm font-semibold tracking-wide">
                                {{ item.name }}
                            </span>
                        </Link>
                    </div>
                </template>
            </nav>

            <div ref="profileMenuRef" class="theme-sidebar-footer relative shrink-0">
                <transition
                    enter-active-class="transition ease-out duration-200"
                    enter-from-class="opacity-0 translate-y-2 scale-95"
                    enter-to-class="opacity-100 translate-y-0 scale-100"
                    leave-active-class="transition ease-in duration-75"
                    leave-from-class="opacity-100 translate-y-0 scale-100"
                    leave-to-class="opacity-0 translate-y-2 scale-95"
                >
                    <div
                        v-if="isProfileMenuOpen"
                        class="absolute bottom-full left-2 right-2 z-[60] mb-1 overflow-hidden rounded-lg border border-slate-200 bg-white shadow-lg dark:border-slate-600 dark:bg-slate-700"
                    >
                        <div
                            class="border-b border-slate-200 px-2.5 py-1.5 dark:border-slate-600"
                        >
                            <p
                                class="text-[9px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400"
                            >
                                Signed in as
                            </p>
                            <p
                                class="truncate text-[11px] font-semibold leading-tight text-slate-800 dark:text-white"
                            >
                                {{ $page.props.auth.user.name }}
                            </p>
                            <p
                                class="truncate text-[10px] leading-tight text-slate-500 dark:text-slate-400"
                            >
                                {{ $page.props.auth.user.email }}
                            </p>
                        </div>

                        <div class="p-1">
                            <Link
                                :href="route('profile.edit')"
                                class="flex w-full items-center gap-2 rounded-md px-2 py-1.5 text-[11px] font-semibold transition-colors"
                                :class="
                                    isProfileActive()
                                        ? 'bg-indigo-600 text-white'
                                        : 'text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 dark:text-slate-200 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-200'
                                "
                            >
                                <span
                                    class="flex h-6 w-6 items-center justify-center rounded"
                                    :class="
                                        isProfileActive()
                                            ? 'bg-indigo-500/40 text-white'
                                            : 'bg-slate-100 text-indigo-600 dark:bg-slate-600 dark:text-indigo-300'
                                    "
                                >
                                    <svg
                                        class="h-3.5 w-3.5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"
                                        />
                                    </svg>
                                </span>
                                My Profile
                            </Link>

                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="mt-0.5 flex w-full items-center gap-2 rounded-md px-2 py-1.5 text-[11px] font-semibold text-slate-700 transition-colors hover:bg-indigo-50 hover:text-indigo-700 dark:text-slate-200 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-200"
                            >
                                <span
                                    class="flex h-6 w-6 items-center justify-center rounded bg-slate-100 text-indigo-600 dark:bg-slate-600/70 dark:text-indigo-300"
                                >
                                    <svg
                                        class="h-3.5 w-3.5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"
                                        />
                                    </svg>
                                </span>
                                Log Out
                            </Link>
                        </div>
                    </div>
                </transition>

                <button
                    type="button"
                    class="group flex h-full w-full items-center gap-1.5 px-2 transition-colors hover:bg-indigo-500/10"
                    :class="{
                        'bg-indigo-500/10': isProfileMenuOpen,
                    }"
                    @click="isProfileMenuOpen = !isProfileMenuOpen"
                >
                    <div
                        class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-[9px] font-bold text-white"
                    >
                        {{ userInitials() }}
                    </div>
                    <div
                        class="min-w-0 flex-1 overflow-hidden text-left text-slate-800 dark:text-white"
                    >
                        <p class="truncate text-[10px] font-semibold leading-none">
                            {{ $page.props.auth.user.name }}
                        </p>
                        <p
                            class="mt-px truncate text-[9px] capitalize leading-none text-slate-500 dark:text-slate-400"
                        >
                            {{ roleName }}
                        </p>
                    </div>
                    <svg
                        class="h-3 w-3 shrink-0 text-slate-400 transition-transform duration-200"
                        :class="{ 'rotate-180': isProfileMenuOpen }"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                    >
                        <path d="M5 15l7-7 7 7" />
                    </svg>
                </button>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex min-w-0 flex-1 flex-col overflow-hidden">
            <header
                class="z-30 flex h-16 shrink-0 items-center justify-between border-b border-slate-200 bg-white px-4 shadow-sm transition-colors sm:px-5 lg:px-6 dark:border-slate-700 dark:bg-slate-800"
            >
                <div class="flex min-w-0 items-center">
                    <button
                        type="button"
                        class="-ml-2 mr-3 p-2 text-gray-500 lg:hidden dark:text-slate-300"
                        @click="isSidebarOpen = true"
                    >
                        <svg
                            class="h-7 w-7"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="2.5"
                        >
                            <path d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="min-w-0">
                        <h1
                            class="truncate text-sm font-bold leading-none text-slate-800 lg:text-lg dark:text-slate-100"
                        >
                            {{ pageTitle }}
                        </h1>
                    </div>
                </div>

                <div class="flex items-center gap-3 sm:gap-4">
                    <button
                        type="button"
                        class="relative inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-slate-50 text-slate-600 transition-colors hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 dark:border-slate-600 dark:bg-slate-700 dark:text-indigo-200 dark:hover:bg-slate-600"
                        :title="
                            isDark
                                ? 'Switch to light mode'
                                : 'Switch to dark mode'
                        "
                        :aria-label="
                            isDark
                                ? 'Switch to light mode'
                                : 'Switch to dark mode'
                        "
                        @click="toggleDarkMode"
                    >
                        <svg
                            v-if="isDark"
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"
                            />
                        </svg>
                        <svg
                            v-else
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"
                            />
                        </svg>
                    </button>
                    <a
                        href="/"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group hidden h-10 items-center gap-2 bg-slate-100 px-3 text-xs font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:bg-indigo-50 hover:text-indigo-700 hover:shadow sm:inline-flex dark:bg-slate-700 dark:text-slate-200 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-300"
                    >
                        <span
                            class="flex h-5 w-5 items-center justify-center rounded-md bg-white text-slate-500 transition-colors group-hover:text-indigo-600 dark:bg-slate-800 dark:text-slate-300 dark:group-hover:text-indigo-300"
                        >
                            <svg
                                class="h-3.5 w-3.5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M13.5 3H21m0 0v7.5M21 3l-9.75 9.75M7.5 6H6A2.25 2.25 0 003.75 8.25v9.75A2.25 2.25 0 006 20.25h9.75A2.25 2.25 0 0018 18v-1.5"
                                />
                            </svg>
                        </span>
                        View Site
                    </a>
                </div>
            </header>

            <main
                class="theme-content-bg custom-scrollbar min-h-0 flex-1 overflow-x-hidden overflow-y-auto transition-colors"
            >
                <div class="theme-page-shell">
                    <slot />
                </div>
            </main>

            <footer class="theme-page-footer">
                <div class="font-bold uppercase tracking-widest">
                    Sample Project for Laravel Inertia Vue3 Admin Dashboard
                    Layout
                </div>
            </footer>
        </div>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
    height: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.05);
    border-radius: 10px;
}
aside .custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(148, 163, 184, 0.3);
}
</style>
